<?php

namespace Tests\Feature;

use App\Address;
use App\Pharmacy;
use App\User;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class AddressAuthorizationTest extends TestCase
{
    use MakesGraphQLRequests;

    /** @test */
    public function it_requires_not_only_the_permission_but_also_the_correct_pharmacy_id_to_query_address()
    {
        $address = factory(Address::class)->create();
        $pharmacy_id = $address->pharmacy->id;

        User::setPharmacyIds([$pharmacy_id + 1]);
        $query = "{
        address(id: {$address->id}) {
            street
        }
        }";
        $this->graphQL($query)->assertSee('You are not authorized');

        User::setPharmacyIds([$pharmacy_id]);
        $query = "{
        address(id: {$address->id}) {
            street
        }
        }";
        $this->graphQL($query)->assertOk()->assertJsonFragment([
            'street' => $address->street
        ]);
    }

    /** @test */
    public function it_requires_permission_to_createAddress()
    {
        $pharmacy = factory(Pharmacy::class)->create();
        $this->graphQL("mutation {
        createAddress(input: {
            street: \"street\"
            pharmacy: {
                connect: {$pharmacy->id}
            }
        }) {
            street
        }
        }")->assertSee('You are not authorized');

        User::setMyAbilities(['createAddress']);

        $this->graphQL("mutation {
        createAddress(input: {
            street: \"street\"
            pharmacy: {
                connect: {$pharmacy->id}
            }
        }) {
            street
        }
        }")->assertSee('You are not authorized');

        User::setPharmacyIds([$pharmacy->id]);

        $this->graphQL("mutation {
        createAddress(input: {
            street: \"street\"
            pharmacy: {
                connect: {$pharmacy->id}
            }
        }) {
            street
        }
        }")->assertJsonFragment([
            'street' => 'street'
        ]);
    }

    /** @test */
    public function it_requires_permission_to_query_pharmacy()
    {
        $pharmacy = factory(Pharmacy::class)->create();
        $this->graphQL("{
        pharmacy(id: {$pharmacy->id}) {
            name
        }
        }")->assertSee('You are not authorized');
        User::setPharmacyIds([$pharmacy->id]);
        User::setMyAbilities(['pharmacy']);
        $pharmacy = factory(Pharmacy::class)->create();
        $this->graphQL("{
        pharmacy(id: {$pharmacy->id}) {
            name
        }
        }")->assertJsonFragment([
            'name' => $pharmacy->name
        ]);
    }
}
