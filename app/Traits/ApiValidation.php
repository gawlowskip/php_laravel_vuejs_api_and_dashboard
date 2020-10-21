<?php

namespace App\Traits;

use App\Ad;
use App\Developer;
use App\Lead;
use App\Property;
use App\PropertyFeature;
use App\PropertyImage;
use App\PropertyLocation;
use App\PropertyVideo;
use App\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ApiValidation
{
    /**
     * Check if User is active
     *
     * @param User $user
     */
    protected function checkIfUserActive(User $user)
    {
        if (!$user->active) {
            throw new HttpException(422, trans('response.the_specified_user_is_not_active'));
        }
    }

    /**
     * Check if Developer is the owner of the Ad
     *
     * @param Developer $developer
     * @param Ad $ad
     */
    protected function checkIfDeveloperIsOwner(Developer $developer, Ad $ad)
    {
        if ($developer->id != $ad->developer_id) {
            throw new HttpException(422, trans('response.owner_is_not_the_owner_of_the_item', ['owner' => $this->getShortClassName(Developer::class), 'item' => $this->getShortClassName(Ad::class)]));
        }
    }

    /**
     * Check if Stripe keys exists
     *
     * @param $stripeKey
     * @param $stripeSecret
     */
    protected function checkIfStripeKeysExists($stripeKey, $stripeSecret)
    {
        if (!$stripeKey || !$stripeSecret) {
            throw new HttpException(400, trans('response.stripe_key_or_stripe_secret_are_not_defined'));
        }
    }

    /**
     * Check if difference between two dates is lower than X days
     *
     * @param $fromDate
     * @param $toDate
     * @param $diffInDays
     */
    protected function checkIfDiffBetweenTwoDatesIsLowerThanXDays($fromDate, $toDate, $diffInDays)
    {
        if ($toDate->diffInDays($fromDate) > $diffInDays) {
            throw new HttpException(422, trans('response.the_period_of_time_cannot_be_longer_than_x_days', ['days' => $diffInDays]));
        }
    }

    /**
     * Check if Ad has an image
     *
     * @param $ad
     */
    protected function checkIfAdHasImage($ad)
    {
        if (!$ad->image) {
            throw new HttpException(404, trans('response.the_specified_item_has_not_a_stored_feature', ['item' => $this->getShortClassName(Ad::class), 'feature' => $this->getShortClassName(PropertyImage::class)]));
        }
    }

    /**
     * Check if User is the owner of the Ad
     *
     * @param User $user
     * @param Ad $ad
     */
    protected function checkIfUserIsAdOwner(User $user, Ad $ad)
    {
        if ($user->id != $ad->developer_id) {
            throw new HttpException(422, trans('response.owner_is_not_the_owner_of_the_item', ['owner' => $this->getShortClassName(User::class), 'item' => $this->getShortClassName(Ad::class)]));
        }
    }

    /**
     * Check if feature exists
     *
     * @param $feature
     */
    protected function checkIfFeatureExists($feature)
    {
        if (empty($feature)) {
            throw new HttpException(404, trans('response.the_specified_item_has_not_a_stored_feature', ['item' => $this->getShortClassName(Property::class), 'feature' => $this->getShortClassName(PropertyFeature::class)]));
        }
    }

    /**
     * Check if feature belongs to property
     *
     * @param Property $property
     * @param PropertyFeature $feature
     */
    protected function checkIfFeatureBelongsToProperty(Property $property, PropertyFeature $feature)
    {
        if ($property->feature->id != $feature->id) {
            throw new HttpException(422, trans('response.item_is_not_belongs_to_the_owner', ['item' => $this->getShortClassName(PropertyFeature::class), 'owner' => $this->getShortClassName(Property::class)]));
        }
    }

    /**
     * Check if image exists
     *
     * @param $image
     */
    protected function checkIfImageExists($image)
    {
        if (empty($image)) {
            throw new HttpException(404, trans('response.the_specified_item_has_not_a_stored_feature', ['item' => $this->getShortClassName(Property::class), 'feature' => $this->getShortClassName(PropertyImage::class)]));
        }
    }

    /**
     * Check if image belongs to property
     *
     * @param Property $property
     * @param PropertyImage $image
     */
    protected function checkIfImageBelongsToProperty(Property $property, PropertyImage $image)
    {
        $propertyImage = $property->images->where('id', $image->id)->first();
        if (!isset($propertyImage->id) || $propertyImage->id != $image->id) {
            throw new HttpException(422, trans('response.item_is_not_belongs_to_the_owner', ['item' => $this->getShortClassName(PropertyImage::class), 'owner' => $this->getShortClassName(Property::class)]));
        }
    }

    /**
     * Check if location exists
     *
     * @param $location
     */
    protected function checkIfLocationExists($location)
    {
        if (empty($location)) {
            throw new HttpException(404, trans('response.the_specified_item_has_not_a_stored_feature', ['item' => $this->getShortClassName(Property::class), 'feature' => $this->getShortClassName(PropertyLocation::class)]));
        }
    }

    /**
     * Check if location belongs to property
     *
     * @param Property $property
     * @param PropertyLocation $location
     */
    protected function checkIfLocationBelongsToProperty(Property $property, PropertyLocation $location)
    {
        if ($property->location->id != $location->id) {
            throw new HttpException(422, trans('response.item_is_not_belongs_to_the_owner', ['item' => $this->getShortClassName(PropertyLocation::class), 'owner' => $this->getShortClassName(Property::class)]));
        }
    }

    /**
     * Check if video exists
     *
     * @param $video
     */
    protected function checkIfVideoExists($video)
    {
        if (empty($video)) {
            throw new HttpException(404, trans('response.the_specified_item_has_not_a_stored_feature', ['item' => $this->getShortClassName(Property::class), 'feature' => $this->getShortClassName(PropertyVideo::class)]));
        }
    }

    /**
     * Check if video belongs to property
     *
     * @param Property $property
     * @param PropertyVideo $video
     */
    protected function checkIfVideoBelongsToProperty(Property $property, PropertyVideo $video)
    {
        $propertyVideo = $property->videos->where('id', $video->id)->first();
        if (!isset($propertyVideo->id) || $propertyVideo->id != $video->id) {
            throw new HttpException(422, trans('response.item_is_not_belongs_to_the_owner', ['item' => $this->getShortClassName(PropertyVideo::class), 'owner' => $this->getShortClassName(Property::class)]));
        }
    }

    /**
     * Check if user is the owner of the lead
     *
     * @param User $user
     * @param Lead $lead
     */
    protected function checkIfUserIsLeadOwner(User $user, Lead $lead)
    {
        if ($user->id != $lead->user_id && $user->facebook_id != $lead->user_id) {
            throw new HttpException(422, trans('response.owner_is_not_the_owner_of_the_item', ['owner' => $this->getShortClassName(User::class), 'item' => $this->getShortClassName(Lead::class)]));
        }
    }

    /**
     * Get short class name for specified model
     *
     * @param string $className
     * @return string|string[]
     */
    protected function getShortClassName(string $className)
    {
        return str_replace("App\\", "", $className);
    }
}