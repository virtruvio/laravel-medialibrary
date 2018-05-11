<?php

namespace Spatie\MediaLibrary\Tests\Feature\ResponsiveImages;

use Spatie\MediaLibrary\Tests\TestCase;
use Spatie\MediaLibrary\ResponsiveImages\RegisteredResponsiveImages;

class RegisteredResponsiveImagesTest extends TestCase
{
    /** @test */
    public function it_will_register_generated_responsive_images_in_the_db()
    {
        $this->testModel
            ->addMedia($this->getTestJpg())
            ->withResponsiveImages()
            ->toMediaCollection();

        $media = $this->testModel->getFirstMedia();

        $this->assertEquals([
            'test___ml_bri_340_280.jpg',
            'test___ml_bri_284_233.jpg',
            'test___ml_bri_237_195.jpg',
        ], $media->responsive_images['ml_bri']['urls']);
    }

    /** @test */
    public function it_can_render_a_srcset_when_the_base64svg_is_not_rendered_yet()
    {
        $this->testModel
            ->addMedia($this->getTestJpg())
            ->withResponsiveImages()
            ->toMediaCollection();

        $media = $this->testModel->getFirstMedia();

        $responsiveImages = $media->responsive_images;

        unset($responsiveImages['ml_bri']['base64svg']);

        $media->responsive_images = $responsiveImages;

        $registeredResponsiveImage = new RegisteredResponsiveImages($media);

        $this->assertNull($registeredResponsiveImage->getPlaceholderSvg());

        $this->assertNotEmpty($registeredResponsiveImage->getSrcset());
    }
}
