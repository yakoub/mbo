<?php
namespace App\Command;

use App\Entity\Person;

trait LdapImportTrait {

  function ldap() {
    $conn = ldap_connect('ldap://sedc01.solaredge.local', 636);
    if (!$conn) {
      return false;
    }
    $username = 'Solaredge';
    $password = '';

    if (empty($username) or empty($password)) {
      throw new \Exception('missing ldap settings');
    }
    $auth = @ldap_bind($conn, $username, $password); 
    if (!$auth) {
      return false;
    }

    ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
    return $conn;
  }

  abstract protected function getManager();
  abstract protected function getRepository();

  function search_parameters() {
    $base_dn = 'ou=Users, ou=Solaredge, dc=solaredge, dc=local';
    $attr = array(
      'mailNickname', 'department', 'c', 'manager', 'extensionAttribute1', 'telephoneNumber',
      'mail', 'mobile', 'name', 'title', 'whenChanged', 'objectCategory', 'cn', 'objectClass',
    );

    $filter = '';
    $filter .= '(objectCategory=person)';
    $filter .= '(objectClass=user)';
    /*$last_time = new \DateTime();
    $ux_time = \Drupal::state()->get('portal_org.users_import');
    if ($ux_time) {
      $last_time->setTimestamp($ux_time);
      //$filter .= '(whenChanged>=' . $last_time->format('Ymd000000') . '.0Z)';
    }
    \Drupal::state()->set('portal_org.users_import', REQUEST_TIME);*/

    $filter = '(&' . $filter . ')';

    return [$base_dn, $attr, $filter];
  }

  function saveUser($entry) {
    if (empty($entry['mail'])) {
      return;
    }
    $values = array(
      'mail' => $entry['mail'][0],
    );
    $values['name'] = empty($entry['mailnickname']) ? $entry['cn'][0] : $entry['mailnickname'][0];

    $user = $this->getRepository()->findOneBy(['name' => $values['name']]);
    if (!$user) {
        $user = new Person();
        $user->setName($values['name']);
        $user->setEmail($values['mail']);
    }
    $this->extractFields($user, $entry, $values);
    $this->getManager()->persist($user);
  }

  function extractFields($user, $entry, $values) {
    $user->setFullName(iconv('WINDOWS-1252', 'UTF-8', $entry['name'][0]));
    if (isset($entry['title'])) {
      $user->setTitle(iconv('WINDOWS-1252', 'UTF-8', $entry['title'][0]));
    }
    $this->saveOrg($entry, $user);
  }

  function saveOrg($entry, $user) {
    $dnr = ldap_explode_dn($entry['dn'], 1);
    $dnr = array_slice($dnr, 0, -4);
    array_shift($dnr); // count
    $cn = array_shift($dnr);
    $office = array_pop($dnr);
    if (!$cn or !$office) {
      // danielle.vn
      return;
    }
    if ($dnr) {
      $user->setLdapName($cn . ',' . $dnr[0]);
    }
    else {
      $user->setLdapName($cn . ',' . $office);
    }
  }
  
  function saveUserManager($entry) {
    if (!isset($entry['mailnickname']) or empty($entry['manager'][0])) {
      return;
    }
    if ($entry['mailnickname'][0] == 'danielle.vn') {  // unresolved sql bug
      return;
    }
    $dnr = ldap_explode_dn($entry['manager'][0], 1);
    array_shift($dnr);
    $manager_ldap_name = $dnr[0] . ',' . $dnr[1];

    $dnr = ldap_explode_dn($entry['dn'], 1);
    array_shift($dnr);
    $ldap_name = $dnr[0] . ',' . $dnr[1];

    $users = $this->getRepository()->findBy(['ldap_name' => [$manager_ldap_name, $ldap_name]]);
    
    if ($users) {
      foreach ($users as $uid => $user) {
        if ($user->getLdapName() == $ldap_name) {
          $updated_user = $user;
        }
        elseif ($user->getLdapName() == $manager_ldap_name) {
          $manager_user = $user;
        }
      }
      if (!isset($manager_user) or !isset($updated_user)) {
        return;
      }
      $updated_user->setManager($manager_user);
      $updated_user->setReviewer($manager_user);
    }
  }
}
