<?php

use App\Ad;
use App\AdArea;
use App\Agreement;
use App\Area;
use App\Lead;
use App\Property;
use App\PropertyFeature;
use App\PropertyImage;
use App\PropertyLocation;
use App\PropertyVideo;
use App\Session;
use App\Traits\ApiDatabase;
use App\User;
use App\Visit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use ApiDatabase;

    /**
     * Clear
     */
    private function clear()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Property::truncate();
        PropertyLocation::truncate();
        PropertyFeature::truncate();
        PropertyImage::truncate();
        PropertyVideo::truncate();
        Ad::truncate();
        Lead::truncate();
        Area::truncate();
        Agreement::truncate();
        Visit::truncate();
        Session::truncate();

        User::flushEventListeners();
        Property::flushEventListeners();
        PropertyLocation::flushEventListeners();
        PropertyFeature::flushEventListeners();
        PropertyImage::flushEventListeners();
        PropertyVideo::flushEventListeners();
        Ad::flushEventListeners();
        Lead::flushEventListeners();
        Area::flushEventListeners();
        Agreement::flushEventListeners();
        Visit::flushEventListeners();
        Session::flushEventListeners();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clear();

        $usersQuantity = 100;
        $propertiesQuantity = 200;
        $locationsQuantity = 100;
        $featuresQuantity = 100;
        $imagesQuantity = 500;
        $videosQuantity = 500;
        $adsQuantity = 200;
        $leadsQuantity = 500;
        $areaQuantity = Area::AREA_QUANTITY;
        $adAreaQuantity = $adsQuantity;
        $agreementsQuantity = rand($usersQuantity / 2, $usersQuantity);
        $visitQuantity = $propertiesQuantity * 2;
        $sessionQuantity = $usersQuantity * 5;

        $this->customFactory(User::class, $usersQuantity);
        $this->customFactory(Property::class, $propertiesQuantity);
        $this->customFactory(PropertyLocation::class, $locationsQuantity);
        $this->customFactory(PropertyFeature::class, $featuresQuantity);
        $this->customFactory(PropertyImage::class, $imagesQuantity);
        $this->customFactory(PropertyVideo::class, $videosQuantity);
        $this->customFactory(Ad::class, $adsQuantity);
        $this->customFactory(Lead::class, $leadsQuantity);
        $this->customFactory(Area::class, $areaQuantity);
        $this->customFactory(AdArea::class, $adAreaQuantity);

        if ($this->command->confirm("Do you wish to generate test data for " . Agreement::class . " with Stripe payment for Developers?")) {
            $this->customFactory(Agreement::class, $agreementsQuantity);
        }

        $this->customFactory(Visit::class, $visitQuantity);
        $this->customFactory(Session::class, $sessionQuantity);
    }
}