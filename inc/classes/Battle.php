<?php

class Battle
{
    private $maxRounds = 5;

    public $units = [];

    private $sideIds = [];

    public $side1 = []; // counts of units
    public $side1Power = []; // all attack, shield and health

    public $side2 = [];
    public $side2Power = [];

    private $log = [];

    public $result = null;

    /**
     * Defines new unit for future use in fight
     * @param string $name
     * @param integer $attack
     * @param integer $shield
     * @param integer $health
     */
    public function defineUnit($name, $attack, $shield, $health)
    {
        if (isset($this->units[$name])) {
            return;
        } else {
            $this->units[$name] = [$attack, $shield, $health];
        }
    }

    /**
     * $sides = [[
     *  "unitName1" => 50, // count
     *  "unitName2" => 15
     * ], [
     *  "unitName3" => 45
     * ]];
     * @param array $sides Counts of units on both sides
     * @param integer $side1Id Id of first side for detail result.
     * @param integer $side2Id Id of second side for detail result.
     */
    public function setSides($sides, $side1Id = null, $side2Id = null)
    {
        $this->sideIds = [$side1Id, $side2Id];
        $this->side1 = $sides[0];
        $this->side2 = $sides[1];
        $this->side1Power = $this->calculateSide(1);
        $this->side2Power = $this->calculateSide(2);
    }

    /**
     * unitNames must be defined before battle
     * @param int $luck
     */
    public function fight($luck = null)
    {
        $round = 1;

        if (!$luck) {
            $luck = random_int(-20, 20);
        }

        $side1Temp = $this->side1Power;
        $side2Temp = $this->side2Power;

        while ($round <= $this->maxRounds) {
            // final damage
            $tempDefenseSide1 = $side1Temp[1] - $side2Temp[0];
            $tempDefenseSide2 = $side2Temp[1] - $side1Temp[0];

            // decrease health
            $side1Temp[2] += $tempDefenseSide1 < 0 ? $tempDefenseSide1 : 0;
            $side2Temp[2] += $tempDefenseSide2 < 0 ? $tempDefenseSide2 : 0;

            // optimise defense
            if ($tempDefenseSide1 < 0) {
                $side1Def = $this->increaseDeffense(1, $round);
            } else {
                $side1Def = $tempDefenseSide1 + $this->increaseDeffense(1, $round);
            }
            if ($tempDefenseSide2 < 0) {
                $side2Def = $this->increaseDeffense(2, $round);
            } else {
                $side2Def = $tempDefenseSide2 + $this->increaseDeffense(2, $round);
            }

            // optimise damage
            $side1Temp = $this->resolveDamage(1, $side1Temp[2], $luck);
            $side1Temp[1] = $side1Def;
            $side2Temp = $this->resolveDamage(2, $side2Temp[2], $luck);
            $side2Temp[1] = $side2Def;

            // log round
            $this->log[] = ["round" => $round, "side1" => $this->side1, "side2" => $this->side2];
            // winner?
            if ($side1Temp[2] <= 0 && $side2Temp[2] <= 0) {
                $this->makeResult($luck);
                break;
            } elseif ($side1Temp[2] <= 0) {
                $this->makeResult($luck, 2);
                break;
            } elseif ($side2Temp[2] <= 0) {
                $this->makeResult($luck, 1);
                break;
            }

            // next round
            $round++;
        }
        // tied
        if (!$this->result) {
            $this->makeResult($luck);
        }
    }

    /**
     * Return increase of defense for specific side in round
     *
     * @param integer $side Side in battle (1|2)
     * @param integer $round Number of round in fight
     * @return integer Positive number of increase
     */
    private function increaseDeffense($side, $round)
    {
        $side = "side" . $side . "Power";
        return floor($this->$side[1] / (2.5 * (1 + log($round * 1.1))));
    }

    /**
     * Return calculated damage for specific side
     *
     * @param integer $sideNum Side in battle (1|2)
     * @param integer $newHealth Summary of health after enemy attack
     * @return integer Damage for next round
     */
    private function resolveDamage($sideNum, $newHealth, $luck)
    {
        $side = "side" . $sideNum;
        $sidePower = $side . "Power";
        $sideTemp = $this->$sidePower;

        if (($newHealth + ($newHealth * ($sideNum * 2 - 3) * $luck / 300)) <= $sideTemp[2]) {
            $newHealth += $newHealth * ($sideNum * 2 - 3) * $luck / 300;
        }

        $lastUnit = null;
        while (($newHealth + 8) < $sideTemp[2]) {
            $unit = $this->getLostUnit($sideNum);
            if ($unit) {
                $this->$side[$unit]--;
                $sideTemp = $this->calculateSide($sideNum);
                $lastUnit = $unit;
            } else {
                if ($sideTemp[2] > 0 && $lastUnit) {
                    $this->$side[$lastUnit]++;
                    $sideTemp = $this->calculateSide($sideNum);
                }
                break;
            }
        }

        return $sideTemp;
    }

    /**
     * Choose right unit to die for specific side.
     *
     * todo: Should be optimised
     *
     * @param integer $sideNum
     * @return null|string
     */
    private function getLostUnit($sideNum)
    {
        $side = "side" . $sideNum;
        $random = random_int(0, 100000) / 100000;
        $allUnitsCount = 0;
        // count of all units
        foreach ($this->$side as $name => $count) {
            $allUnitsCount += $count;
        }
        // no units left
        if ($allUnitsCount <= 0) {
            return null;
        }
        // select specific unit
        $prevTempUnitsRatio = 0;
        $tempUnitsRatio = 0;
        foreach ($this->$side as $name => $count) {
            $tempUnitsRatio += $count / $allUnitsCount;
            if ($random > $prevTempUnitsRatio && $random < $tempUnitsRatio) {
                return $name;
            }
        }
        return null;
    }

    /**
     * Calculate power for specific side.
     *
     * @param integer $side
     * @return array
     */
    private function calculateSide($side)
    {
        $side = "side" . $side;

        $attack = 0;
        $defense = 0;
        $health = 0;
        foreach ($this->$side as $key => $count) {
            $attack += $this->units[$key][0] * $count;
            $defense += $this->units[$key][1] * $count;
            $health += $this->units[$key][2] * $count;
        }
        return [$attack, $defense, $health];
    }

    /**
     * Detail result of battle.
     *
     * @param integer $luck Negative number is better for side1, positive number is better for side2
     * @param integer|null $winner
     */
    private function makeResult($luck, $winner = null)
    {
        $this->result = ["winner" => $winner, "winnerId" => $winner ? $this->sideIds[$winner - 1] : null, "luck" => $luck, "log" => $this->log];
    }

    /**
     * Get result of battle. Returns null when fight has not been called.
     *
     * @return array|null Result of battle
     */
    public function getResult()
    {
        return $this->result;
    }

}