<?php

namespace bonP\reservationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationeventControllerTest extends WebTestCase
{
    public function testCreatereservationevent ()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/CreateReservationEvent ');
    }

    public function testDeletereservationevent ()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/DeleteReservationEvent ');
    }

    public function testUpdatereservationevent ()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/UpdateReservationEvent ');
    }

    public function testAffichereservationevent ()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/AfficheReservationEvent ');
    }

}
