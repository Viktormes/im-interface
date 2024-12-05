<?php

namespace App\Enums;

enum MeasurementCategory: string
{
    case Lungs = 'lungs';
    case Heart = 'heart';
    case Fitness = 'fitness';
    case Sleep = 'sleep';
    case Other = 'other';
}
