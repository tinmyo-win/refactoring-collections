<?php

use function PHPSTORM_META\type;

include('../Arr.php');
include('Aspect.php');

class TestArr
{
  private function passedMessage()
  {
    echo "<span style='color:green;'> Test Passed<br> </span>";
  }

  private function failedMessage()
  {
    echo "<span style='color:red;'> Test Failed<br> </span>";
  }

  private function resultMessage($expectValue, $resultValue)
  {
    echo "Result Value => " . $resultValue . "<br>" .  "Expected Value => " . $expectValue . "<br>";
  }

  private function assertEqual($expectValue, $resultValue)
  {
    if ($resultValue === $expectValue) {
      $this->passedMessage();
    } else {
      $this->failedMessage();
    }
    $this->resultMessage($resultValue, $expectValue);
  }

  private function arrayAssertEqual($expectValue, $resultValue)
  {
    $isEqual = $this->areArraysEqual($resultValue, $expectValue);
    if ($isEqual) {
      $this->passedMessage();
    } else {
      $this->failedMessage();
    }
    $this->resultMessage(json_encode($resultValue), json_encode($expectValue));
  }

  private function areArraysEqual($array1, $array2)
  {
    if (count($array1) !== count($array2) || $this->isAssoc($array1) !== $this->isAssoc($array2)) {
      return false;
    }

    if ((string) json_encode($array1) === (string) json_encode($array2)) return true;

    $diff = array_diff($array1, $array2);
    return empty($diff);
  }

  private function isAssoc(array $arr)
  {
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
  }


  public function testRetreiveFirstElement()
  {
    $grades = [39, 70, 20, 45, 51];
    $arr = new Arr();

    $expectValue = $grades[0];
    $resultValue = $arr->first($grades);
    $this->assertEqual($resultValue, $expectValue);

    $expectValueFromCallback = $grades[1]; //first grade that greater than 40
    $resultValueFromCallback = $arr->first($grades, function ($grade) {
      if ($grade > 40) return true;
      return false;
    });
    $this->assertEqual($resultValueFromCallback, $expectValueFromCallback);

    $resultValueWhenEmpty = $arr->first([]);
    $expectValueWhenEmpty = null;
    $this->assertEqual($resultValueWhenEmpty, $expectValueWhenEmpty);
  }

  public function testRetreiveLastElement()
  {
    $grades = [39, 70, 30, 50, 25];
    $arr = new Arr();

    $expectValue = $grades[count($grades) - 1];
    $resultValue = $arr->last($grades);
    $this->assertEqual($resultValue, $expectValue);

    $expectValueFromCallback = $grades[3]; //last grade that greater than 40
    $resultValueFromCallback = $arr->last($grades, function ($grade) {
      if ($grade > 40) return true;
      return false;
    });
    $this->assertEqual($resultValueFromCallback, $expectValueFromCallback);

    $resultValueWhenEmpty = $arr->last([]);
    $expectValueWhenEmpty = null;
    $this->assertEqual($resultValueWhenEmpty, $expectValueWhenEmpty);
  }

  public function testArrMap()
  {
    $grades = [39, 70, 30, 50, 25];
    $arr = new Arr();

    $expectValue = [];
    foreach ($grades as $grade) {
      $expectValue[] = $grade + 10;
    }
    $resultValue = $arr->map($grades, function ($value) {
      return $value + 10;
    });
    $this->arrayAssertEqual($resultValue, $expectValue);

    $resultValueWithKeys = $arr->map($grades, function ($value, $key) {
      return [$key + 10 => $value + 10];
    });
    $expectValueWithKeys = [];
    foreach ($grades as $key => $grade) {
      $expectValueWithKeys[] = [$key + 10 => $grade + 10];
    }
    $this->arrayAssertEqual($resultValueWithKeys, $expectValueWithKeys);

    $emptyArr = [];
    $resultValueWhenNull = $arr->map($emptyArr, function ($value, $key) {
      return [$key + 10 => $value + 10];
    });
    $expectValueWhenNull = [];
    $this->arrayAssertEqual($resultValueWhenNull, $expectValueWhenNull);
  }

  public function testArrPluck()
  {
    $developers = array(
      array("developer" => array("id" => 1, "name" => "Taylor")),
      array("developer" => array("id" => 2, "name" => "Abigail")),
      array("developer" => array("id" => 3, "name" => "Eric")),
      array("developer" => array("id" => 4, "name" => "Samantha")),
      array("developer" => array("id" => 5, "name" => "Matthew")),
      array("developer" => array("id" => 6, "name" => "Olivia")),
      array("developer" => array("id" => 7, "name" => "William")),
      array("developer" => array("id" => 8, "name" => "Sophia")),
      array("developer" => array("id" => 9, "name" => "Michael")),
      array("developer" => array("id" => 10, "name" => "Isabella"))
    );

    $arr = new Arr();

    $expectValue = [];
    foreach ($developers as $developer) {
      $expectValue[] = $developer["developer"]["name"];
    }
    $resultValue = $arr->pluck($developers, "developer.name");
    $this->arrayAssertEqual($resultValue, $expectValue);

    $expectValueWithKey = [];
    foreach ($developers as $developer) {
      $expectValueWithKey[$developer["developer"]["id"]] = $developer["developer"]["name"];
    }
    $resultValueWithKey = $arr->pluck($developers, "developer.name", "developer.id");
    $this->arrayAssertEqual($resultValueWithKey, $expectValueWithKey);

    $expectValueWhenPlucklWrongWord = [];
    for($i = 0 ; $i < count($developers); $i++) {
      $expectValueWhenPlucklWrongWord[] = null;
    }
    $resultValueWhenPluckWrongWord = $arr->pluck($developers, "developer.namename");
    $this->arrayAssertEqual($resultValueWhenPluckWrongWord, $expectValueWhenPlucklWrongWord);

  }

  public function testArrGet()
  {
    $items = ['products' => [
        'desk' => ['price' => 100],
        'table' => ['price' => 250],
      ]];

    $expectValue = ['price' => 100];
    $resultValue = Arr::get($items, 'products.desk');
    $this->arrayAssertEqual($resultValue, $expectValue);

  }
}

$testArr = new TestArr();

$aspect = new Aspect();
$testProxyArr = new Proxy($testArr, $aspect);

$testProxyArr->testRetreiveFirstElement();
$testProxyArr->testRetreiveLastElement();
$testProxyArr->testArrMap();
$testProxyArr->testArrPluck();
$testProxyArr->testArrGet();
