<?php
# Fluent form before submit do calculator for build cost calculator
add_filter('fluentform_insert_response_data', 'tct_my_function', 10, 3);
function tct_my_function($formData, $formId, $inputConfigs)
{
    if ($formId == '18') { // Replace with your Form ID
        $calculatorData = array(
            'not_started' =>  array('new_build' =>  1, 'build_cost_conversion' =>  1.20, 'refurbishment' =>  1.20),
            'part_completed' => array('new_build' =>  1.75, 'build_cost_conversion' =>  2, 'refurbishment' =>  2),
            'completed' => array('new_build' =>  2.50, 'build_cost_conversion' =>  2.75, 'refurbishment' =>  2.75)
        );

        $stage      = $formData['stage'];
        $build_type = $formData['build_type'];
        $build_cost = $formData['build_cost'];
        $final_cost_percentage = $calculatorData[$stage][$build_type];
        if ($final_cost_percentage) {
            $value = str_replace(',', '', $build_cost);
            $value = ($value * $final_cost_percentage) / 100;

            $formatter = new NumberFormatter('en_GB',  NumberFormatter::CURRENCY);
            $build_result = $formatter->formatCurrency($value, 'GBP');
            $formData['build_result'] = $build_result;

            // if build_cost is less than 250,000 then set build_result to 2500
            if ($build_cost < 250000) {
                $formData['build_result'] = $formatter->formatCurrency(2500, 'GBP');
            }

        }
        return $formData;
    }
    return $formData;
}
