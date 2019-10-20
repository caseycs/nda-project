<?php
declare(strict_types=1);

namespace App\UserOnboardingStats;

class StepPercentagesPerWeek
{
    /**
     * @var \DateTime
     */
    private $week;

    /**
     * @var float
     */
    private $step1p;

    /**
     * @var float
     */
    private $step2p;

    /**
     * @var float
     */
    private $step3p;

    /**
     * @var float
     */
    private $step4p;

    /**
     * @var float
     */
    private $step5p;

    /**
     * @var float
     */
    private $step6p;

    /**
     * @var float
     */
    private $step7p;

    /**
     * @var float
     */
    private $step8p;

    public function __construct(
        \DateTime $week,
        float $step1p,
        float $step2p,
        float $step3p,
        float $step4p,
        float $step5p,
        float $step6p,
        float $step7p,
        float $step8p
    ) {
        $this->week = $week;
        $this->step1p = $step1p;
        $this->step2p = $step2p;
        $this->step3p = $step3p;
        $this->step4p = $step4p;
        $this->step5p = $step5p;
        $this->step6p = $step6p;
        $this->step7p = $step7p;
        $this->step8p = $step8p;
    }

    public function getWeek(): \DateTime
    {
        return $this->week;
    }

    /**
     * @return float
     */
    public function getStep1P(): float
    {
        return $this->step1p;
    }

    /**
     * @return float
     */
    public function getStep2P(): float
    {
        return $this->step2p;
    }

    /**
     * @return float
     */
    public function getStep3P(): float
    {
        return $this->step3p;
    }

    /**
     * @return float
     */
    public function getStep4P(): float
    {
        return $this->step4p;
    }

    /**
     * @return float
     */
    public function getStep5P(): float
    {
        return $this->step5p;
    }

    /**
     * @return float
     */
    public function getStep6P(): float
    {
        return $this->step6p;
    }

    /**
     * @return float
     */
    public function getStep7P(): float
    {
        return $this->step7p;
    }

    /**
     * @return float
     */
    public function getStep8P(): float
    {
        return $this->step8p;
    }
}
