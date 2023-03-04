<?php

namespace App\Enums;

enum MeasurementUnit: string
{
    case Standard = 'standard';
    case Metric = 'metric';
    case Imperial = 'imperial';
}
