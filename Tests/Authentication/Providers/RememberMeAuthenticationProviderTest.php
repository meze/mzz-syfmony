<?php


namespace Tests\Authentication\RememberMe;
use Mzz\MzzBundle\Authentication\RememberMeAuthentication;
use Mzz\MzzBundle\Authentication\Providers\RememberMeAuthenticationProvider;
use Mzz\MzzBundle\Authentication\RememberMeToken;

class RememberMeAuthenticationProviderTest extends \PHPUnit_Framework_TestCase
{
    const TOKEN_SERIES = 'series_test_1';
    private $token;

    /**
     * @test
     */
    public function whenGivenCorrectSeriresAndTokenShouldBeSuccessfullyAuthenticated()
    {
        $repository = new StubUserRepository;
        $token = $this->createToken('user', 'abc');

        $auth = $this->mockAuth($token);
        $auth->expects($this->once())
            ->method('setAuthenticated')
            ->with($this->equalTo(true));

        $provider = new RememberMeAuthenticationProvider;
        $provider->setUserRepository($repository);
        $provider->authenticate($auth);
    }

    /**
     * @test
     */
    public function whenTokenIsIncorrectShouldNotBeAuthenticated_PrincipalShouldNotBeChanged()
    {
        $repository = new StubUserRepository;
        $auth = $this->mockAuth(null);

        $auth->expects($this->never())
            ->method('setAuthenticated');

        $auth->expects($this->never())
            ->method('setPrincipal');

        $provider = new RememberMeAuthenticationProvider;
        $provider->setUserRepository($repository);
        $provider->authenticate($auth);
    }

    /**
     * @test
     * @expectedException Mzz\MzzBundle\Authentication\Exceptions\ThiefAssumedException
     */
    public function whenTokensAreNotEqualThiefIsAssumed()
    {
        $repository = new StubUserRepository;
        $token = $this->createToken('user', 'abc');
        $repository->token = 'anothertoken';

        $auth = $this->mockAuth($token);

        $auth->expects($this->never())
            ->method('setAuthenticated');

        $auth->expects($this->never())
            ->method('setPrincipal');

        $provider = new RememberMeAuthenticationProvider;
        $provider->setUserRepository($repository);
        $provider->authenticate($auth);
    }



    /**
     * @test
     */
    public function whenSuccessfullyAuthenticatedPrincipalChangesToTokenPrincipalFromRepository()
    {
        $repository = new StubUserRepository;
        $token = $this->createToken('user', 'abc');
        $auth = $this->mockAuth($token);

        $auth->expects($this->once())
            ->method('setPrincipal')
            ->with($this->equalTo('user_from_repository'));

        $provider = new RememberMeAuthenticationProvider;
        $provider->setUserRepository($repository);
        $provider->authenticate($auth);
    }


    /**
     * @test
     */
    public function supportsOnlyRememberMeAuthentication()
    {
        $provider = new RememberMeAuthenticationProvider();
        $authA = $this->getMock('Mzz\MzzBundle\Authentication\Authentication', array(), array('', '', array()));
        $authB = new RememberMeAuthentication('', '', '', array());

        $this->assertFalse($provider->supports($authA));
        $this->assertTrue($provider->supports($authB));
    }

    private function mockAuth($token, $series = RememberMeAuthenticationProviderTest::TOKEN_SERIES)
    {
        $auth =  $this->getMock('Mzz\MzzBundle\Authentication\RememberMeAuthentication', array(), array('user', $series, $token, array('ADMIN')));

        $auth->expects($this->any())
            ->method('getTokenSeries')
            ->will($this->returnValue($series));

        $auth->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($token));
        return $auth;
    }

    private function createToken($id, $token)
    {
        $this->token = new RememberMeToken($id, $token, RememberMeAuthenticationProviderTest::TOKEN_SERIES);
        return $this->token;
    }
}


class StubUserRepository implements \Mzz\MzzBundle\Authentication\UserRepository
{
    public $token;
    public function createEmptyUser() {}
    public function findRememberMeTokenByUserIdAndToken($id, $token)
    {
        if ($token)
            return new RememberMeToken('user_from_repository', $this->token ? $this->token : $token->getToken(), RememberMeAuthenticationProviderTest::TOKEN_SERIES);
    }

    public function findByUsername($username) {}


    public function createNewRememberMeTokenFor($user) {}
    public function removeAllRememberMeTokensFor($user) {}
    public function removeRememberMeToken($user, $token) {}
}
