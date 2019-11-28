<?php

namespace Drupal\Tests\star_wars_core\Functional;

/**
 * Class ExampleFunctionalTest.
 *
 * @group StarWarsCore
 */
class ExampleFunctionalTest extends StarWarsCoreFunctionalTestBase {

  /**
   * @dataProvider dataProviderAdd
   */
  public function testAdd($a, $b, $expected, $excpectExceptionMessage = NULL) {
    if ($excpectExceptionMessage) {
      $this->setExpectedException(\Exception::class, $excpectExceptionMessage);
    }

    // Replace below with a call to your class method.
    $actual = $a + $b;

    $this->assertEquals($expected, $actual);
  }

  public function dataProviderAdd() {
    return [
      [0, 0, 0],
      [1, 1, 2],
    ];
  }

}
