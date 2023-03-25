<?php

include('../Arr.php');
include('Aspect.php');

class TestArr
{
  private function passedMessage() {
    echo "<span style='color:green;'> Test Passed<br> </span>";
  }

  private function failedMessage() {
    echo "<span style='color:red;'> Test Failed<br> </span>";
  }

  private function resultMessage($expectValue, $resultValue) {
    echo "Result Value => " . $resultValue . "<br>" .  "Expected Value => " . $expectValue . "<br>";
  }

  private function assertEqual($expectValue, $resultValue) {
    if($resultValue === $expectValue) {
      $this->passedMessage();
    } else {
      $this->failedMessage();
    }
    $this->resultMessage($resultValue, $expectValue);
  }

  public function testRetreiveFirstElement() {
    $grades = [39, 70, 20, 45, 51];
    $arr = new Arr();

    $expectValue = $grades[0];
    $resultValue = $arr->first($grades);
    $this->assertEqual($resultValue, $expectValue);

    $expectValueFromCallback = $grades[1]; //first grade that greater than 40
    $resultValueFromCallback = $arr->first($grades, function($grade) {
      if($grade > 40) return true;
      return false;
    });
    $this->assertEqual($resultValueFromCallback, $expectValueFromCallback);

    $resultValueWhenEmpty = $arr->first([]);
    $expectValueWhenEmpty = null;
    $this->assertEqual($resultValueWhenEmpty, $expectValueWhenEmpty);
  }

  public function testRetreiveLastElement() {
    $grades = [39, 70, 30, 50, 25];
    $arr = new Arr();

    $expectValue = $grades[count($grades) - 1];
    $resultValue = $arr->last($grades);
    $this->assertEqual($resultValue, $expectValue);

    $expectValueFromCallback = $grades[3]; //last grade that greater than 40
    $resultValueFromCallback = $arr->last($grades, function($grade) {
      if($grade > 40) return true;
      return false;
    });
    $this->assertEqual($resultValueFromCallback, $expectValueFromCallback);

    $resultValueWhenEmpty = $arr->last([]);
    $expectValueWhenEmpty = null;
    $this->assertEqual($resultValueWhenEmpty, $expectValueWhenEmpty);
  }

}

$testArr = new TestArr();

$aspect = new Aspect();
$testProxyArr = new Proxy($testArr, $aspect);

$testProxyArr->testRetreiveFirstElement();
$testProxyArr->testRetreiveLastElement();
