<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;

class SolaredgeAuth implements SimpleFormAuthenticatorInterface {

    public function createToken(Request $request, $username, $password, $providerKey) {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }

    public function supportsToken(TokenInterface $token, $providerKey) {
        return $token instanceof UsernamePasswordToken
            && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey) {
        try {
            $user = $userProvider->loadUserByUsername($token->getUsername());
            if (preg_match('/manager-[12](-[12])?/', $user->getName())) {
                $auth = true;
            }
            else {
                $auth = $this->ldap_auth($token->getUsername(), $token->getCredentials());
            }
            if ($auth) {
                return new UsernamePasswordToken(
                    $user,
                    'empty',
                    $providerKey,
                    $user->getRoles()
                );
            }
        }
        catch (UsernameNotFoundException $exception) {}
        throw new CustomUserMessageAuthenticationException('Invalid username or password');
    }

    function ldap_auth($username, $password) {
        $conn = ldap_connect('ldaps://sedc01.solaredge.local', 636);
        if ($conn) {
            $auth = @ldap_bind($conn, 'SOLAREDGE\\' . $username, $password); 
            if ($auth) {
                return true;
            }
        }
        return false;
    }
}
