<?php

namespace Zenstruck\Browser\Tests;

use Zenstruck\Browser\Tests\Component\EmailTests;
use Zenstruck\Browser\Tests\Extension\HttpTests;
use Zenstruck\Browser\Tests\Extension\JsonTests;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
trait BrowserKitBrowserTests
{
    use BrowserTests, EmailTests, HttpTests, JsonTests, ProfileAwareTests;

    /**
     * @test
     */
    public function following_redirect_follows_all_by_default(): void
    {
        $this->browser()
            ->interceptRedirects()
            ->visit('/redirect1')
            ->assertOn('/redirect1')
            ->followRedirect()
            ->assertOn('/page1')
            ->assertSuccessful()
        ;
    }

    /**
     * @test
     */
    public function can_re_enable_following_redirects(): void
    {
        $this->browser()
            ->interceptRedirects()
            ->visit('/redirect1')
            ->assertOn('/redirect1')
            ->followRedirects()
            ->visit('/redirect1')
            ->assertOn('/page1')
        ;
    }

    /**
     * @test
     */
    public function calling_follow_redirects_when_the_response_is_a_redirect_follows_the_redirect(): void
    {
        $this->browser()
            ->interceptRedirects()
            ->visit('/redirect1')
            ->followRedirects()
            ->assertOn('/page1')
            ->interceptRedirects()
            ->visit('/page1')
            ->followRedirects()
            ->assertOn('/page1')
        ;
    }

    /**
     * @test
     */
    public function calling_follow_redirects_before_a_request_has_been_made_just_enables_following_redirects(): void
    {
        $this->browser()
            ->followRedirects()
            ->visit('/redirect1')
            ->assertOn('/page1')
        ;
    }

    /**
     * @test
     */
    public function can_limit_redirects_followed(): void
    {
        $this->browser()
            ->interceptRedirects()
            ->visit('/redirect1')
            ->assertOn('/redirect1')
            ->assertRedirected()
            ->followRedirect(1)
            ->assertOn('/redirect2')
            ->assertRedirected()
            ->followRedirect(1)
            ->assertOn('/redirect3')
            ->assertRedirected()
            ->followRedirect(1)
            ->assertOn('/page1')
            ->assertSuccessful()
        ;
    }

    /**
     * @test
     */
    public function assert_redirected_to_follows_all_redirects_by_default(): void
    {
        $this->browser()
            ->interceptRedirects()
            ->visit('/redirect1')
            ->assertRedirectedTo('/page1')
        ;
    }

    /**
     * @test
     */
    public function assert_redirected_to_can_configure_number_of_redirects_to_follow(): void
    {
        $this->browser()
            ->interceptRedirects()
            ->visit('/redirect1')
            ->assertRedirectedTo('/redirect2', 1)
        ;
    }
}
