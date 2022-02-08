<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSymbolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unit_symbols = array(
            array('id' => '1','created_at' => NULL,'updated_at' => NULL,'value' => '0','literal' => 'none','description' => 'Dimension less quantity, e.g. count, per unit, etc.'),
            array('id' => '2','created_at' => NULL,'updated_at' => NULL,'value' => '2','literal' => 'm','description' => 'Length in metres.'),
            array('id' => '3','created_at' => NULL,'updated_at' => NULL,'value' => '3','literal' => 'kg','description' => 'Mass in kilograms. Note: multiplier “k” is
included in this unit symbol for compatibility with IEC 61850-7-3.'),
            array('id' => '4','created_at' => NULL,'updated_at' => NULL,'value' => '4','literal' => 's','description' => 'Time in seconds.'),
            array('id' => '5','created_at' => NULL,'updated_at' => NULL,'value' => '5','literal' => 'A','description' => 'Current in amperes.'),
            array('id' => '6','created_at' => NULL,'updated_at' => NULL,'value' => '6','literal' => 'K','description' => 'Temperature in kelvins.'),
            array('id' => '7','created_at' => NULL,'updated_at' => NULL,'value' => '7','literal' => 'mol','description' => 'Amount of substance in moles.'),
            array('id' => '8','created_at' => NULL,'updated_at' => NULL,'value' => '8','literal' => 'cd','description' => 'Luminous intensity in candelas.'),
            array('id' => '9','created_at' => NULL,'updated_at' => NULL,'value' => '9','literal' => 'deg','description' => 'Plane angle in degrees.'),
            array('id' => '10','created_at' => NULL,'updated_at' => NULL,'value' => '10','literal' => 'rad','description' => 'Plane angle in radians (m/m).'),
            array('id' => '11','created_at' => NULL,'updated_at' => NULL,'value' => '11','literal' => 'sr','description' => 'Solid angle in steradians (m2/m2).'),
            array('id' => '12','created_at' => NULL,'updated_at' => NULL,'value' => '21','literal' => 'Gy','description' => 'Absorbed dose in grays (J/kg).'),
            array('id' => '13','created_at' => NULL,'updated_at' => NULL,'value' => '22','literal' => 'Bq','description' => 'Radioactivity in becquerels (1/s).'),
            array('id' => '14','created_at' => NULL,'updated_at' => NULL,'value' => '23','literal' => 'degC','description' => 'Relative temperature in degrees Celsius.
In the SI unit system the symbol is °C. Electric charge is measured in coulomb that has the unit symbol C. To distinguish degree Celsius from coulomb the symbol used in the UML is degC. The reason for not using °C is that the special character ° is difficult to manage in software.'),
            array('id' => '15','created_at' => NULL,'updated_at' => NULL,'value' => '24','literal' => 'Sv','description' => 'Dose equivalent in sieverts (J/kg).'),
            array('id' => '16','created_at' => NULL,'updated_at' => NULL,'value' => '25','literal' => 'F','description' => 'Electric capacitance in farads (C/V).'),
            array('id' => '17','created_at' => NULL,'updated_at' => NULL,'value' => '26','literal' => 'C','description' => 'Electric charge in coulombs (A·s).'),
            array('id' => '18','created_at' => NULL,'updated_at' => NULL,'value' => '27','literal' => 'S','description' => 'Conductance in siemens.'),
            array('id' => '19','created_at' => NULL,'updated_at' => NULL,'value' => '28','literal' => 'H','description' => 'Electric inductance in henrys (Wb/A).'),
            array('id' => '20','created_at' => NULL,'updated_at' => NULL,'value' => '29','literal' => 'V','description' => 'Electric potential in volts (W/A).'),
            array('id' => '21','created_at' => NULL,'updated_at' => NULL,'value' => '30','literal' => 'ohm','description' => 'Electric resistance in ohms (V/A).'),
            array('id' => '22','created_at' => NULL,'updated_at' => NULL,'value' => '31','literal' => 'J','description' => 'Energy in joules (N·m = C·V = W·s).'),
            array('id' => '23','created_at' => NULL,'updated_at' => NULL,'value' => '32','literal' => 'N','description' => 'Force in newtons (kg·m/s2).'),
            array('id' => '24','created_at' => NULL,'updated_at' => NULL,'value' => '33','literal' => 'Hz','description' => 'Frequency in hertz (1/s).'),
            array('id' => '25','created_at' => NULL,'updated_at' => NULL,'value' => '34','literal' => 'lx','description' => 'Illuminance in lux (lm/m2).'),
            array('id' => '26','created_at' => NULL,'updated_at' => NULL,'value' => '35','literal' => 'lm','description' => 'Luminous flux in lumens (cd·sr).'),
            array('id' => '27','created_at' => NULL,'updated_at' => NULL,'value' => '36','literal' => 'Wb','description' => 'Magnetic flux in webers (V·s).'),
            array('id' => '28','created_at' => NULL,'updated_at' => NULL,'value' => '37','literal' => 'T','description' => 'Magnetic flux density in teslas (Wb/m2).'),
            array('id' => '29','created_at' => NULL,'updated_at' => NULL,'value' => '38','literal' => 'W','description' => 'Real power in watts (J/s). Electrical power may have real and reactive components. The real
portion of electrical power (I2R or VIcos(phi)), is expressed in Watts. See also apparent power
and reactive power.'),
            array('id' => '30','created_at' => NULL,'updated_at' => NULL,'value' => '39','literal' => 'Pa','description' => 'Pressure in pascals (N/m2). Note: the absolute or relative measurement of pressure is implied with this entry. See below for more explicit
forms.'),
            array('id' => '31','created_at' => NULL,'updated_at' => NULL,'value' => '41','literal' => 'm2','description' => 'Area in square metres (m2).'),
            array('id' => '32','created_at' => NULL,'updated_at' => NULL,'value' => '42','literal' => 'm3','description' => 'Volume in cubic metres (m3).'),
            array('id' => '33','created_at' => NULL,'updated_at' => NULL,'value' => '43','literal' => 'mPers','description' => 'Velocity in metres per second (m/s).'),
            array('id' => '34','created_at' => NULL,'updated_at' => NULL,'value' => '44','literal' => 'mPers2','description' => 'Acceleration in metres per second squared (m/s2).'),
            array('id' => '35','created_at' => NULL,'updated_at' => NULL,'value' => '45','literal' => 'm3Pers','description' => 'Volumetric flow rate in cubic metres per second (m3/s).'),
            array('id' => '36','created_at' => NULL,'updated_at' => NULL,'value' => '46','literal' => 'mPerm3','description' => 'Fuel efficiency in metres per cubic metres (m/m3).'),
            array('id' => '37','created_at' => NULL,'updated_at' => NULL,'value' => '47','literal' => 'kgm','description' => 'Moment of mass in kilogram metres (kg·m) (first moment of mass). Note: multiplier “k” is included in this unit symbol for compatibility with
IEC 61850-7-3.'),
            array('id' => '38','created_at' => NULL,'updated_at' => NULL,'value' => '48','literal' => 'kgPerm3','description' => 'Density in kilogram/cubic metres (kg/m3). Note: multiplier “k” is included in this unit symbol for compatibility with IEC 61850-7-3.'),
            array('id' => '39','created_at' => NULL,'updated_at' => NULL,'value' => '49','literal' => 'm2Pers','description' => 'Viscosity in square metres / second (m2/s).'),
            array('id' => '40','created_at' => NULL,'updated_at' => NULL,'value' => '50','literal' => 'WPermK','description' => 'Thermal conductivity in watt/metres kelvin.'),
            array('id' => '41','created_at' => NULL,'updated_at' => NULL,'value' => '51','literal' => 'JPerK','description' => 'Heat capacity in joules/kelvin.'),
            array('id' => '42','created_at' => NULL,'updated_at' => NULL,'value' => '52','literal' => 'ppm','description' => 'Concentration in parts per million.'),
            array('id' => '43','created_at' => NULL,'updated_at' => NULL,'value' => '53','literal' => 'rotPers','description' => 'Rotations per second (1/s). See also Hz (1/s).'),
            array('id' => '44','created_at' => NULL,'updated_at' => NULL,'value' => '54','literal' => 'radPers','description' => 'Angular velocity in radians per second (rad/s).'),
            array('id' => '45','created_at' => NULL,'updated_at' => NULL,'value' => '55','literal' => 'WPerm2','description' => 'Heat flux density, irradiance, watts per square metre.'),
            array('id' => '46','created_at' => NULL,'updated_at' => NULL,'value' => '56','literal' => 'JPerm2','description' => 'Insulation energy density, joules per square metre or watt second per square metre.'),
            array('id' => '47','created_at' => NULL,'updated_at' => NULL,'value' => '57','literal' => 'SPerm','description' => 'Conductance per length (F/m).'),
            array('id' => '48','created_at' => NULL,'updated_at' => NULL,'value' => '58','literal' => 'KPers','description' => 'Temperature change rate in kelvins per second.'),
            array('id' => '49','created_at' => NULL,'updated_at' => NULL,'value' => '59','literal' => 'PaPers','description' => 'Pressure change rate in pascals per second.'),
            array('id' => '50','created_at' => NULL,'updated_at' => NULL,'value' => '60','literal' => 'JPerkgK','description' => 'Specific heat capacity, specific entropy, joules per kilogram Kelvin.'),
            array('id' => '51','created_at' => NULL,'updated_at' => NULL,'value' => '61','literal' => 'VA','description' => 'Apparent power in volt amperes. See also real power and reactive power.'),
            array('id' => '52','created_at' => NULL,'updated_at' => NULL,'value' => '63','literal' => 'VAr','description' => 'Reactive power in volt amperes reactive. The “reactive” or “imaginary” component of electrical power (VIsin(phi)). (See also real power and
apparent power).
Note: Different meter designs use different
methods to arrive at their results. Some meters may compute reactive power as an arithmetic value, while others compute the value
vectorially. The data consumer should determine the method in use and the suitability of the
measurement for the intended purpose.'),
            array('id' => '53','created_at' => NULL,'updated_at' => NULL,'value' => '65','literal' => 'cosPhi','description' => 'Power factor, dimensionless.
NOTE 1    This definition of power factor only
holds for balanced systems. See the alternative definition under code 153.
NOTE 2    Beware of differing sign conventions in use between the IEC and EEI. It is assumed that the data consumer understands the type of
meter in use and the sign convention in use by the utility.'),
            array('id' => '54','created_at' => NULL,'updated_at' => NULL,'value' => '66','literal' => 'Vs','description' => 'Volt seconds (Ws/A).'),
            array('id' => '55','created_at' => NULL,'updated_at' => NULL,'value' => '67','literal' => 'V2','description' => 'Volt squared (W2/A2).'),
            array('id' => '56','created_at' => NULL,'updated_at' => NULL,'value' => '68','literal' => 'As','description' => 'Ampere seconds (A·s).'),
            array('id' => '57','created_at' => NULL,'updated_at' => NULL,'value' => '69','literal' => 'A2','description' => 'Amperes squared (A2).'),
            array('id' => '58','created_at' => NULL,'updated_at' => NULL,'value' => '70','literal' => 'A2s','description' => 'Ampere squared time in square amperes (A2s).'),
            array('id' => '59','created_at' => NULL,'updated_at' => NULL,'value' => '71','literal' => 'VAh','description' => 'Apparent energy in volt ampere hours.'),
            array('id' => '60','created_at' => NULL,'updated_at' => NULL,'value' => '72','literal' => 'Wh','description' => 'Real energy in watt hours.'),
            array('id' => '61','created_at' => NULL,'updated_at' => NULL,'value' => '73','literal' => 'VArh','description' => 'Reactive energy in volt ampere reactive hours.'),
            array('id' => '62','created_at' => NULL,'updated_at' => NULL,'value' => '74','literal' => 'VPerHz','description' => 'Magnetic flux in volt per hertz.'),
            array('id' => '63','created_at' => NULL,'updated_at' => NULL,'value' => '75','literal' => 'HzPers','description' => 'Rate of change of frequency in hertz per second.'),
            array('id' => '64','created_at' => NULL,'updated_at' => NULL,'value' => '76','literal' => 'character','description' => 'Number of characters.'),
            array('id' => '65','created_at' => NULL,'updated_at' => NULL,'value' => '77','literal' => 'charPers','description' => 'Data rate (baud) in characters per second.'),
            array('id' => '66','created_at' => NULL,'updated_at' => NULL,'value' => '78','literal' => 'kgm2','description' => 'Moment of mass in kilogram square metres
(kg·m2) (Second moment of mass, commonly
called the moment of inertia). Note: multiplier “k” is included in this unit symbol for compatibility
with IEC 61850-7-3.'),
            array('id' => '67','created_at' => NULL,'updated_at' => NULL,'value' => '79','literal' => 'dB','description' => 'Sound pressure level in decibels. Note: multiplier “d” is included in this unit symbol for
compatibility with IEC 61850-7-3.'),
            array('id' => '68','created_at' => NULL,'updated_at' => NULL,'value' => '81','literal' => 'WPers','description' => 'Ramp rate in watts per second.'),
            array('id' => '69','created_at' => NULL,'updated_at' => NULL,'value' => '82','literal' => 'lPers','description' => 'Volumetric flow rate in litres per second.'),
            array('id' => '70','created_at' => NULL,'updated_at' => NULL,'value' => '83','literal' => 'dBm','description' => 'Power level (logarithmic ratio of signal strength, Bel-mW), normalized to 1mW. Note: multiplier “d” is included in this unit symbol for
compatibility with IEC 61850-7-3.'),
            array('id' => '71','created_at' => NULL,'updated_at' => NULL,'value' => '84','literal' => 'h','description' => 'Time in hours, hour = 60 min = 3600 s.'),
            array('id' => '72','created_at' => NULL,'updated_at' => NULL,'value' => '85','literal' => 'min','description' => 'Time in minutes, minute = 60 s.'),
            array('id' => '73','created_at' => NULL,'updated_at' => NULL,'value' => '100','literal' => 'Q','description' => 'Quantity power, Q.'),
            array('id' => '74','created_at' => NULL,'updated_at' => NULL,'value' => '101','literal' => 'Qh','description' => 'Quantity energy, Qh.'),
            array('id' => '75','created_at' => NULL,'updated_at' => NULL,'value' => '102','literal' => 'ohmm','description' => 'Resistivity, ohm metres, (rho).'),
            array('id' => '76','created_at' => NULL,'updated_at' => NULL,'value' => '103','literal' => 'APerm','description' => 'A/m, magnetic field strength, amperes per metre.'),
            array('id' => '77','created_at' => NULL,'updated_at' => NULL,'value' => '104','literal' => 'V2h','description' => 'Volt-squared hour, volt-squared-hours.'),
            array('id' => '78','created_at' => NULL,'updated_at' => NULL,'value' => '105','literal' => 'A2h','description' => 'Ampere-squared hour, ampere-squared hour.'),
            array('id' => '79','created_at' => NULL,'updated_at' => NULL,'value' => '106','literal' => 'Ah','description' => 'Ampere-hours, ampere-hours.'),
            array('id' => '80','created_at' => NULL,'updated_at' => NULL,'value' => '111','literal' => 'count','description' => 'Amount of substance, Counter value.'),
            array('id' => '81','created_at' => NULL,'updated_at' => NULL,'value' => '119','literal' => 'ft3','description' => 'Volume, cubic feet.'),
            array('id' => '82','created_at' => NULL,'updated_at' => NULL,'value' => '125','literal' => 'm3Perh','description' => 'Volumetric flow rate, cubic metres per hour.'),
            array('id' => '83','created_at' => NULL,'updated_at' => NULL,'value' => '128','literal' => 'gal','description' => 'Volume in gallons, US gallon (1 gal = 231 in3 = 128 fl ounce).'),
            array('id' => '84','created_at' => NULL,'updated_at' => NULL,'value' => '132','literal' => 'Btu','description' => 'Energy, British Thermal Units.'),
            array('id' => '85','created_at' => NULL,'updated_at' => NULL,'value' => '134','literal' => 'l','description' => 'Volume in litres, litre = dm3 = m3/1 000.'),
            array('id' => '86','created_at' => NULL,'updated_at' => NULL,'value' => '137','literal' => 'lPerh','description' => 'Volumetric flow rate, litres per hour.'),
            array('id' => '87','created_at' => NULL,'updated_at' => NULL,'value' => '143','literal' => 'lPerl','description' => 'Concentration, The ratio of the volume of a solute divided by the volume of the solution.
Note: Users may need use a prefix such a ‘µ’ to express a quantity such as ‘µL/L’.'),
            array('id' => '88','created_at' => NULL,'updated_at' => NULL,'value' => '144','literal' => 'gPerg','description' => 'Concentration, The ratio of the mass of a solute divided by the mass of the solution. Note: Users may need use a prefix such a ‘µ’ to express a
quantity such as ‘µg/g’.'),
            array('id' => '89','created_at' => NULL,'updated_at' => NULL,'value' => '145','literal' => 'molPerm3','description' => 'Concentration, The amount of substance concentration, (c), the amount of solvent in
moles divided by the volume of solution in m³.'),
            array('id' => '90','created_at' => NULL,'updated_at' => NULL,'value' => '146','literal' => 'molPermol','description' => 'Concentration, Molar fraction, the ratio of the molar amount of a solute divided by the molar amount of the solution.'),
            array('id' => '91','created_at' => NULL,'updated_at' => NULL,'value' => '147','literal' => 'molPerkg','description' => 'Concentration, Molality, the amount of solute in moles and the amount of solvent in kilograms.'),
            array('id' => '92','created_at' => NULL,'updated_at' => NULL,'value' => '149','literal' => 'sPers','description' => 'Time, Ratio of time. Note: Users may need to
supply a prefix such as ‘µ’ to show rates such as ‘µs/s’.'),
            array('id' => '93','created_at' => NULL,'updated_at' => NULL,'value' => '150','literal' => 'HzPerHz','description' => 'Frequency, rate of frequency change. Note:
Users may need to supply a prefix such as ‘m’ to show rates such as ‘mHz/Hz’.'),
            array('id' => '94','created_at' => NULL,'updated_at' => NULL,'value' => '151','literal' => 'VPerV','description' => 'Voltage, ratio of voltages. Note: Users may need to supply a prefix such as ‘m’ to show rates such as ‘mV/V’.'),
            array('id' => '95','created_at' => NULL,'updated_at' => NULL,'value' => '152','literal' => 'APerA','description' => 'Current, ratio of amperages. Note: Users may
need to supply a prefix such as ‘m’ to show rates such as ‘mA/A’.'),
            array('id' => '96','created_at' => NULL,'updated_at' => NULL,'value' => '153','literal' => 'VPerVA','description' => 'Power factor, PF, the ratio of the active power to the apparent power. Note: The sign convention used for power factor will differ between IEC
meters and EEI (ANSI) meters. It is assumed that the data consumers understand the type of meter being used and agree on the sign
convention in use at any given utility.'),
            array('id' => '97','created_at' => NULL,'updated_at' => NULL,'value' => '154','literal' => 'rev','description' => 'Amount of rotation, revolutions.'),
            array('id' => '98','created_at' => NULL,'updated_at' => NULL,'value' => '158','literal' => 'kat','description' => 'Catalytic activity, katal = mol / s.'),
            array('id' => '99','created_at' => NULL,'updated_at' => NULL,'value' => '165','literal' => 'JPerkg','description' => 'Specific energy, Joules / kg.'),
            array('id' => '100','created_at' => NULL,'updated_at' => NULL,'value' => '166','literal' => 'm3Uncompensated','description' => 'Volume, cubic metres, with the value uncompensated for weather effects.'),
            array('id' => '101','created_at' => NULL,'updated_at' => NULL,'value' => '167','literal' => 'm3Compensated','description' => 'Volume, cubic metres, with the value compensated for weather effects.'),
            array('id' => '102','created_at' => NULL,'updated_at' => NULL,'value' => '168','literal' => 'WPerW','description' => 'Signal Strength, ratio of power. Note: Users may need to supply a prefix such as ‘m’ to show rates such as ‘mW/W’.'),
            array('id' => '103','created_at' => NULL,'updated_at' => NULL,'value' => '169','literal' => 'therm','description' => 'Energy, therms.'),
            array('id' => '104','created_at' => NULL,'updated_at' => NULL,'value' => '173','literal' => 'onePerm','description' => 'Wavenumber, reciprocal metres, (1/m).'),
            array('id' => '105','created_at' => NULL,'updated_at' => NULL,'value' => '174','literal' => 'm3Perkg','description' => 'Specific volume, cubic metres per kilogram, v.'),
            array('id' => '106','created_at' => NULL,'updated_at' => NULL,'value' => '175','literal' => 'Pas','description' => 'Dynamic viscosity, pascal seconds.'),
            array('id' => '107','created_at' => NULL,'updated_at' => NULL,'value' => '176','literal' => 'Nm','description' => 'Moment of force, newton metres.'),
            array('id' => '108','created_at' => NULL,'updated_at' => NULL,'value' => '177','literal' => 'NPerm','description' => 'Surface tension, newton per metre.'),
            array('id' => '109','created_at' => NULL,'updated_at' => NULL,'value' => '178','literal' => 'radPers2','description' => 'Angular acceleration, radians per second squared.'),
            array('id' => '110','created_at' => NULL,'updated_at' => NULL,'value' => '181','literal' => 'JPerm3','description' => 'Energy density, joules per cubic metre.'),
            array('id' => '111','created_at' => NULL,'updated_at' => NULL,'value' => '182','literal' => 'VPerm','description' => 'Electric field strength, volts per metre.'),
            array('id' => '112','created_at' => NULL,'updated_at' => NULL,'value' => '183','literal' => 'CPerm3','description' => 'Electric charge density, coulombs per cubic metre.'),
            array('id' => '113','created_at' => NULL,'updated_at' => NULL,'value' => '184','literal' => 'CPerm2','description' => 'Surface charge density, coulombs per square metre.'),
            array('id' => '114','created_at' => NULL,'updated_at' => NULL,'value' => '185','literal' => 'FPerm','description' => 'Permittivity, farads per metre.'),
            array('id' => '115','created_at' => NULL,'updated_at' => NULL,'value' => '186','literal' => 'HPerm','description' => 'Permeability, henrys per metre.'),
            array('id' => '116','created_at' => NULL,'updated_at' => NULL,'value' => '187','literal' => 'JPermol','description' => 'Molar energy, joules per mole.'),
            array('id' => '117','created_at' => NULL,'updated_at' => NULL,'value' => '188','literal' => 'JPermolK','description' => 'Molar entropy, molar heat capacity, joules per mole kelvin.'),
            array('id' => '118','created_at' => NULL,'updated_at' => NULL,'value' => '189','literal' => 'CPerkg','description' => 'Exposure (x rays), coulombs per kilogram.'),
            array('id' => '119','created_at' => NULL,'updated_at' => NULL,'value' => '190','literal' => 'GyPers','description' => 'Absorbed dose rate, grays per second.'),
            array('id' => '120','created_at' => NULL,'updated_at' => NULL,'value' => '191','literal' => 'WPersr','description' => 'Radiant intensity, watts per steradian.'),
            array('id' => '121','created_at' => NULL,'updated_at' => NULL,'value' => '192','literal' => 'WPerm2sr','description' => 'Radiance, watts per square metre steradian.'),
            array('id' => '122','created_at' => NULL,'updated_at' => NULL,'value' => '193','literal' => 'katPerm3','description' => 'Catalytic activity concentration, katals per cubic metre.'),
            array('id' => '123','created_at' => NULL,'updated_at' => NULL,'value' => '195','literal' => 'd','description' => 'Time in days, day = 24 h = 86 400 s.'),
            array('id' => '124','created_at' => NULL,'updated_at' => NULL,'value' => '196','literal' => 'anglemin','description' => 'Plane angle, minutes.'),
            array('id' => '125','created_at' => NULL,'updated_at' => NULL,'value' => '197','literal' => 'anglesec','description' => 'Plane angle, seconds.'),
            array('id' => '126','created_at' => NULL,'updated_at' => NULL,'value' => '198','literal' => 'ha','description' => 'Area, hectares.'),
            array('id' => '127','created_at' => NULL,'updated_at' => NULL,'value' => '199','literal' => 'tonne','description' => 'Mass in tons, “tonne” or “metric ton” (1000 kg = 1 Mg).'),
            array('id' => '128','created_at' => NULL,'updated_at' => NULL,'value' => '214','literal' => 'bar','description' => 'Pressure in bars, (1 bar = 100 kPa).'),
            array('id' => '129','created_at' => NULL,'updated_at' => NULL,'value' => '215','literal' => 'mmHg','description' => 'Pressure, millimetres of mercury (1 mmHg is approximately 133.3 Pa).'),
            array('id' => '130','created_at' => NULL,'updated_at' => NULL,'value' => '217','literal' => 'M','description' => 'Length, nautical miles (1 M = 1 852 m).'),
            array('id' => '131','created_at' => NULL,'updated_at' => NULL,'value' => '219','literal' => 'kn','description' => 'Speed, knots (1 kn = 1 852/3 600) m/s.'),
            array('id' => '132','created_at' => NULL,'updated_at' => NULL,'value' => '276','literal' => 'Mx','description' => 'Magnetic flux, maxwells (1 Mx = 10-8 Wb).'),
            array('id' => '133','created_at' => NULL,'updated_at' => NULL,'value' => '277','literal' => 'G','description' => 'Magnetic flux density, gausses (1 G = 10-4 T).'),
            array('id' => '134','created_at' => NULL,'updated_at' => NULL,'value' => '278','literal' => 'Oe','description' => 'Magnetic field in oersteds, (1 Oe = (103/4p) A/m).'),
            array('id' => '135','created_at' => NULL,'updated_at' => NULL,'value' => '280','literal' => 'Vh','description' => 'Volt-hour, Volt hours.'),
            array('id' => '136','created_at' => NULL,'updated_at' => NULL,'value' => '0','literal' => 'WPerA','description' => 'Active power per current flow, watts per Ampere.'),
            array('id' => '137','created_at' => NULL,'updated_at' => NULL,'value' => '0','literal' => 'onePerHz','description' => 'Reciprocal of frequency (1/Hz).'),
            array('id' => '138','created_at' => NULL,'updated_at' => NULL,'value' => '0','literal' => 'VPerVAr','description' => 'Power factor, PF, the ratio of the active power to the apparent power. Note: The sign convention used for power factor will differ between IEC
meters and EEI (ANSI) meters. It is assumed that the data consumers understand the type of meter being used and agree on the sign
convention in use at any given utility.'),
            array('id' => '139','created_at' => NULL,'updated_at' => NULL,'value' => '86','literal' => 'ohmPerm','description' => 'Electric resistance per length in ohms per metre ((V/A)/m).'),
            array('id' => '140','created_at' => NULL,'updated_at' => NULL,'value' => '0','literal' => 'kgPerJ','description' => 'Weight per energy in kilograms per joule (kg/J). Note: multiplier “k” is included in this unit symbol for compatibility with IEC 61850-7-3.'),
            array('id' => '141','created_at' => NULL,'updated_at' => NULL,'value' => '0','literal' => 'JPers','description' => 'Energy rate in joules per second (J/s).'),
            array('id' => '142','created_at' => NULL,'updated_at' => NULL,'value' => '3','literal' => 'kgPerm','description' => 'Mass per 1 m'),
            array('id' => '143','created_at' => NULL,'updated_at' => NULL,'value' => '0','literal' => 'RUB','description' => 'Ruble'),
            array('id' => '144','created_at' => NULL,'updated_at' => NULL,'value' => '0','literal' => 'pogm','description' => 'Погонный метр')
        );

        DB::table('unit_symbols')->insert($unit_symbols);
    }
}
