<?php

namespace Database\Seeders;

use App\Constants\System;
use App\Fhir\Base\Resource\DomainResource\Condition;
use App\Fhir\Base\Resource\DomainResource\Organization;
use App\Fhir\Base\Resource\DomainResource\Patient;
use App\Fhir\Base\Resource\DomainResource\Practitioner;
use App\Fhir\Base\Resource\DomainResource\PractitionerRole;
use App\Fhir\Base\Resource\DomainResource\Questionnaire;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $orgSU = Organization::create([
            'resourceType' => 'Organization',
            'active' => true,
            'identifier' => [
                [
                    'use' => 'official',
                    'type' => [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/v2-0203',
                                'code' => 'PRN',
                            ],
                        ],
                        'text' => 'HSA-id',
                    ],
                    'system' => System::EmployeeHsaId,
                    'value' => 'SE2321000131-E000000000109',
                ],
            ],
            'type' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/organization-type',
                            'code' => 'prov',
                            'display' => 'Healthcare Provider',
                        ],
                    ],
                ],
            ],
            'name' => 'Sahlgrenska Universitetssjukhuset',
        ]);
        $this->command->info("Seeded Organization '{$orgSU->name->value}' with id '{$orgSU->_id}'");

        $orgReumatologi = Organization::create([
            'resourceType' => 'Organization',
            'active' => true,
            'identifier' => [
                [
                    'use' => 'official',
                    'type' => [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/v2-0203',
                                'code' => 'PRN',
                            ],
                        ],
                        'text' => 'HSA-id',
                    ],
                    'system' => System::EmployeeHsaId,
                    'value' => 'SE2321000131-E000000001052',
                ],
            ],
            'type' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/organization-type',
                            'code' => 'dept',
                            'display' => 'Hospital Department',
                        ],
                    ],
                ],
            ],
            'name' => 'Reumatologi',
            'partOf' => [
                'reference' => "Organization/$orgSU->_id",
                'display' => $orgSU->name->value,
            ],
        ]);
        $this->command->info("Seeded Organization '{$orgReumatologi->name->value}' with id '{$orgReumatologi->_id}'");

        $orgReumatologiSahlgrenska = Organization::create([
            'resourceType' => 'Organization',
            'active' => true,
            'identifier' => [
                [
                    'use' => 'official',
                    'type' => [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/v2-0203',
                                'code' => 'PRN',
                            ],
                        ],
                        'text' => 'HSA-id',
                    ],
                    'system' => System::EmployeeHsaId,
                    'value' => 'SE2321000131-E000000001059',
                ],
            ],
            'type' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/organization-type',
                            'code' => 'team',
                            'display' => 'Organizational team',
                        ],
                    ],
                ],
            ],
            'name' => 'Reumatologimottagning Sahlgrenska',
            'partOf' => [
                'reference' => "Organization/$orgReumatologi->_id",
                'display' => $orgReumatologi->name->value,
            ],
        ]);
        $this->command->info("Seeded Organization '{$orgReumatologiSahlgrenska->name->value}' with id '{$orgReumatologiSahlgrenska->_id}'");

        $practitioner = Practitioner::create([
            'identifier' => [
                [
                    'use' => 'official',
                    'type' => [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/v2-0203',
                                'code' => 'PRN',
                            ],
                        ],
                        'text' => 'HSA-id',
                    ],
                    'system' => System::EmployeeHsaId,
                    'value' => 'SE000000000000-0000',
                    'assigner' => [
                        'display' => 'ACME',
                    ],
                ],
                [
                    'use' => 'official',
                    'type' => [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/v2-0203',
                                'code' => 'SB',
                            ],
                        ],
                        'text' => 'Swedish Personnummer',
                    ],
                    'system' => System::SwedishPersonalIdentityNumber,
                    'value' => '19123456-1234',
                ],
            ],
            'name' => [
                [
                    'use' => 'official',
                    'family' => 'Olsson',
                    'given' => [
                        'Benny',
                    ],
                    'prefix' => [
                        'Dr.',
                    ],
                ],
                [
                    'use' => 'usual',
                    'given' => [
                        'Ben',
                    ],
                ],
            ],
            'gender' => 'male',
            'birthDate' => '1983-04-01',
            'address' => [
                [
                    'use' => 'work',
                    'type' => 'both',
                    'line' => [
                        'Gatan 1',
                    ],
                    'city' => 'Staden',
                    'postalCode' => '123 45',
                    'country' => 'SE',
                ],
            ],
            'communication' => [
                [
                    'language' => [
                        'coding' => [
                            [
                                'system' => 'urn:ietf:bcp:47',
                                'code' => 'sv',
                                'display' => 'Svenska',
                            ],
                        ],
                    ],
                    'preferred' => true,
                ],
            ],
        ]);

        $practitionerName = collect($practitioner->name)->where(fn ($n) => $n->use->value === 'official')
            ->map(function ($name) {
                return collect($name->given)->map(fn ($n) => $n->value)->join(' ').' '.$name->family->value;
            })
            ->first();

        $this->command->info("Seeded Practitioner '{$practitionerName}' with id '{$practitioner->_id}'");

        $practitionerRole = PractitionerRole::create([
            'resourceType' => 'PractitionerRole',
            'practitioner' => [
                'reference' => "Practitioner/$practitioner->_id",
                'display' => $practitionerName,
            ],
            'organization' => [
                'reference' => "Organization/$orgReumatologiSahlgrenska->_id",
                'display' => $orgReumatologiSahlgrenska->name->value,
            ],
            'code' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/practitioner-role',
                            'code' => 'doctor',
                            'display' => 'Doctor',
                        ],
                        [
                            'system' => 'http://snomed.info/sct',
                            'code' => '45440000',
                            'display' => 'Rheumatologist',
                        ],
                    ],
                    'text' => 'Läkare - Reumatologi',
                ],
            ],
            'specialty' => [
                [
                    'coding' => [
                        [
                            'system' => 'http://snomed.info/sct',
                            'code' => '394810000',
                            'display' => 'Rheumatology',
                        ],
                    ],
                    'text' => 'Reumatologi',
                ],
            ],
        ]);

        $this->command->info("Seeded PractitionerRole '{$practitionerRole->code[0]->text->value}' for '{$practitionerName}' with id '{$practitionerRole->_id}'");

        $patient = Patient::create([
            'resourceType' => 'Patient',
            'id' => '01j91159nwwaszzx15hx4vn9pb',
            'language' => 'sv-SE',
            'meta' => [
                'versionId' => '1',
                'lastUpdated' => '2024-09-30T08:36:11+00:00',
            ],
            'identifier' => [
                [
                    'use' => 'official',
                    'type' => [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/v2-0203',
                                'code' => 'SB',
                            ],
                        ],
                        'text' => 'Swedish Personnummer',
                    ],
                    'system' => System::SwedishPersonalIdentityNumber,
                    'value' => '19121212-1212',
                ],
            ],
            'name' => [
                [
                    'use' => 'official',
                    'family' => 'Andersson',
                    'given' => [
                        'Karl',
                    ],
                ],
                [
                    'use' => 'usual',
                    'given' => [
                        'Kalle',
                    ],
                ],
            ],
            'gender' => 'male',
            'birthDate' => '1988-12-31',
            'address' => [
                [
                    'use' => 'work',
                    'type' => 'both',
                    'line' => [
                        'Gatan 1',
                    ],
                    'city' => 'Staden',
                    'postalCode' => '123 45',
                    'country' => 'SE',
                ],
            ],
            'communication' => [
                [
                    'language' => [
                        'coding' => [
                            [
                                'system' => 'urn:ietf:bcp:47',
                                'code' => 'sv',
                                'display' => 'Swedish',
                            ],
                        ],
                        'text' => 'Svenska',
                    ],
                    'preferred' => true,
                ],
            ],
            'generalPractitioner' => [
                [
                    'reference' => "Practitioner/$practitioner->_id",
                    'display' => $practitionerName,
                ],
            ],
            'managingOrganization' => [
                [
                    'reference' => "Organization/$orgReumatologiSahlgrenska->_id",
                    'display' => $orgReumatologiSahlgrenska->name->value,
                ],
            ],
        ]);

        $patientName = collect($patient->name)->where(fn ($n) => $n->use->value === 'official')
            ->map(function ($name) {
                return collect($name->given)->map(fn ($n) => $n->value)->join(' ').' '.$name->family->value;
            })
            ->first();

        $this->command->info("Seeded Patient '{$patientName}' with id '{$patient->_id}'");

        $condition = Condition::create([
            'resourceType' => 'Condition',
            'language' => 'sv',
            'clinicalStatus' => [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                        'code' => 'active',
                    ],
                ],
            ],
            'verificationStatus' => [
                'coding' => [
                    [
                        'system' => 'http://terminology.hl7.org/CodeSystem/condition-ver-status',
                        'code' => 'confirmed',
                    ],
                ],
            ],
            'subject' => [
                'reference' => "Patient/$patient->_id",
            ],
            'severity' => [
                'coding' => [
                    [
                        'system' => 'http://snomed.info/sct',
                        'code' => '24484000',
                        'display' => 'Severe',
                    ],
                ],
            ],
            'code' => [
                'coding' => [
                    [
                        'system' => 'http://hl7.org/fhir/sid/icd-10',
                        'code' => 'M45',
                        'display' => 'Ankylosing Spondylitis',
                    ],
                ],
                'text' => 'Axial Spondylartrit',
            ],
        ]);

        $this->command->info("Seeded Condition 'Ankylosing Spondylitis' with id '{$condition->_id}'");

        $basdaiQuestionnaire = Questionnaire::create([
            'resourceType' => 'Questionnaire',
            'title' => 'Formulär BASDAI',
            'status' => 'active',
            'subjectType' => [
                'Patient',
            ],
            'date' => '2023-09-19',
            'description' => 'Bath ankyloserande spondylit sjukdomsaktivitetsindex är en validerad diagnostiskt test som tillåter en läkare, vanligtvis en reumatolog, för att bestämma effektiviteten av en aktuell läkemedelsterapi, eller behovet av att införa en ny läkemedelsterapi för behandling av ankyloserande spondylit (AS).',
            'item' => [
                [
                    'linkId' => '1',
                    'text' => 'Den senaste veckan',
                    'type' => 'group',
                    'item' => [
                        [
                            'extension' => [
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxValue',
                                    'valueDecimal' => 10,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/minValue',
                                    'valueDecimal' => 0,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces',
                                    'valueInteger' => 1,
                                ],
                            ],
                            'linkId' => '1.1',
                            'prefix' => '1.',
                            'text' => 'Hur upplevde Du Din trötthet i allmänhet?',
                            'type' => 'decimal',
                            'required' => true,
                            'answerOption' => [
                                [
                                    'valueString' => 'Ingen',
                                ],
                                [
                                    'valueString' => 'Mycket svår',
                                ],
                            ],
                            'initial' => [
                                [
                                    'valueDecimal' => 5,
                                ],
                            ],
                        ],
                        [
                            'extension' => [
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxValue',
                                    'valueDecimal' => 10,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/minValue',
                                    'valueDecimal' => 0,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces',
                                    'valueInteger' => 1,
                                ],
                            ],
                            'linkId' => '1.2',
                            'prefix' => '2.',
                            'text' => 'Hur upplevde Du vanligen Din nack-, rygg- och höftsmärta?',
                            'type' => 'decimal',
                            'required' => true,
                            'answerOption' => [
                                [
                                    'valueString' => 'Ingen',
                                ],
                                [
                                    'valueString' => 'Mycket svår',
                                ],
                            ],
                            'initial' => [
                                [
                                    'valueDecimal' => 5,
                                ],
                            ],
                        ],
                        [
                            'extension' => [
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxValue',
                                    'valueDecimal' => 10,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/minValue',
                                    'valueDecimal' => 0,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces',
                                    'valueInteger' => 1,
                                ],
                            ],
                            'linkId' => '1.3',
                            'prefix' => '3.',
                            'text' => 'Hur upplevde Du i allmänhet Din smärta/svullnad i andra leder än nacke, rygg och höfter?',
                            'type' => 'decimal',
                            'required' => true,
                            'answerOption' => [
                                [
                                    'valueString' => 'Ingen',
                                ],
                                [
                                    'valueString' => 'Mycket svår',
                                ],
                            ],
                            'initial' => [
                                [
                                    'valueDecimal' => 5,
                                ],
                            ],
                        ],
                        [
                            'extension' => [
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxValue',
                                    'valueDecimal' => 10,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/minValue',
                                    'valueDecimal' => 0,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces',
                                    'valueInteger' => 1,
                                ],
                            ],
                            'linkId' => '1.4',
                            'prefix' => '4.',
                            'text' => 'Hur upplevde Du vanligen Ditt obehag från områden, som ömmar för tryck eller beröring?',
                            'type' => 'decimal',
                            'required' => true,
                            'answerOption' => [
                                [
                                    'valueString' => 'Ingen',
                                ],
                                [
                                    'valueString' => 'Mycket svår',
                                ],
                            ],
                            'initial' => [
                                [
                                    'valueDecimal' => 5,
                                ],
                            ],
                        ],
                        [
                            'extension' => [
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxValue',
                                    'valueDecimal' => 10,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/minValue',
                                    'valueDecimal' => 0,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces',
                                    'valueInteger' => 1,
                                ],
                            ],
                            'linkId' => '1.5',
                            'prefix' => '5.',
                            'text' => 'Hur upplevde Du vanligen Din morgonstelhet efter uppvaknandet?',
                            'type' => 'decimal',
                            'required' => true,
                            'answerOption' => [
                                [
                                    'valueString' => 'Ingen',
                                ],
                                [
                                    'valueString' => 'Mycket svår',
                                ],
                            ],
                            'initial' => [
                                [
                                    'valueDecimal' => 5,
                                ],
                            ],
                        ],
                        [
                            'extension' => [
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxValue',
                                    'valueDecimal' => 10,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/minValue',
                                    'valueDecimal' => 0,
                                ],
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxDecimalPlaces',
                                    'valueInteger' => 1,
                                ],
                            ],
                            'linkId' => '1.6',
                            'prefix' => '6.',
                            'text' => 'Hur länge efter uppvaknandet varade Din morgonstelhet i allmänhet?',
                            'type' => 'decimal',
                            'required' => true,
                            'answerOption' => [
                                [
                                    'valueString' => '0h',
                                ],
                                [
                                    'valueString' => '1h',
                                ],
                                [
                                    'valueString' => '2h eller mer',
                                ],
                            ],
                            'initial' => [
                                [
                                    'valueDecimal' => 5,
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'linkId' => '2',
                    'text' => 'Kommentar (frivilligt)',
                    'type' => 'group',
                    'item' => [
                        [
                            'extension' => [
                                [
                                    'url' => 'http://hl7.org/fhir/StructureDefinition/maxValue',
                                    'valueInteger' => 200,
                                ],
                            ],
                            'linkId' => '2.1',
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
        ]);

        $this->command->info("Seeded Questionnaire 'BASDAI' with id '{$basdaiQuestionnaire->_id}'");
    }
}
