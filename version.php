<?php

declare(strict_types=1);

/**
 * @package   mod_matrix
 * @copyright 2022, New Vector Ltd (Trading as Element)
 * @license   SPDX-License-Identifier: Apache-2.0
 */

\defined('MOODLE_INTERNAL') || exit();

/**
 * @see https://docs.moodle.org/dev/Plugin_files#version.php
 * @see https://docs.moodle.org/dev/version.php
 */
$plugin->component = 'mod_matrix';
$plugin->dependencies = [];
$plugin->maturity = MATURITY_BETA;
$plugin->release = '1.0-beta';
$plugin->requires = 2024100701; // Requires Moodle v3.9 (LTS)
$plugin->version = 2022110400; // Plugin released on 4 November 2022
