<?php
/**
 * Lemonway Countries list
 *
 * @package Lemonway
 */

declare(strict_types=1);

namespace Lemonway\Integrations\Form;

/**
 * Class Countries
 *
 * A class to manage and retrieve information about different countries.
 */
class Countries {


	/**
	 * List of countries.
	 *
	 * @var array List of countries with their details.
	 */
	private $countries = array(
		array(
			'name'     => 'Afghanistan',
			'alpha2'   => 'AF',
			'alpha3'   => 'AFG',
			'numeric'  => '004',
			'currency' => array(
				'AFN',
			),
		),
		array(
			'name'     => 'Åland Islands',
			'alpha2'   => 'AX',
			'alpha3'   => 'ALA',
			'numeric'  => '248',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Albania',
			'alpha2'   => 'AL',
			'alpha3'   => 'ALB',
			'numeric'  => '008',
			'currency' => array(
				'ALL',
			),
		),
		array(
			'name'     => 'Algeria',
			'alpha2'   => 'DZ',
			'alpha3'   => 'DZA',
			'numeric'  => '012',
			'currency' => array(
				'DZD',
			),
		),
		array(
			'name'     => 'American Samoa',
			'alpha2'   => 'AS',
			'alpha3'   => 'ASM',
			'numeric'  => '016',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Andorra',
			'alpha2'   => 'AD',
			'alpha3'   => 'AND',
			'numeric'  => '020',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Angola',
			'alpha2'   => 'AO',
			'alpha3'   => 'AGO',
			'numeric'  => '024',
			'currency' => array(
				'AOA',
			),
		),
		array(
			'name'     => 'Anguilla',
			'alpha2'   => 'AI',
			'alpha3'   => 'AIA',
			'numeric'  => '660',
			'currency' => array(
				'XCD',
			),
		),
		array(
			'name'     => 'Antarctica',
			'alpha2'   => 'AQ',
			'alpha3'   => 'ATA',
			'numeric'  => '010',
			'currency' => array(
				'ARS',
				'AUD',
				'BGN',
				'BRL',
				'BYR',
				'CLP',
				'CNY',
				'CZK',
				'EUR',
				'GBP',
				'INR',
				'JPY',
				'KRW',
				'NOK',
				'NZD',
				'PEN',
				'PKR',
				'PLN',
				'RON',
				'RUB',
				'SEK',
				'UAH',
				'USD',
				'UYU',
				'ZAR',
			),
		),
		array(
			'name'     => 'Antigua and Barbuda',
			'alpha2'   => 'AG',
			'alpha3'   => 'ATG',
			'numeric'  => '028',
			'currency' => array(
				'XCD',
			),
		),
		array(
			'name'     => 'Argentina',
			'alpha2'   => 'AR',
			'alpha3'   => 'ARG',
			'numeric'  => '032',
			'currency' => array(
				'ARS',
			),
		),
		array(
			'name'     => 'Armenia',
			'alpha2'   => 'AM',
			'alpha3'   => 'ARM',
			'numeric'  => '051',
			'currency' => array(
				'AMD',
			),
		),
		array(
			'name'     => 'Aruba',
			'alpha2'   => 'AW',
			'alpha3'   => 'ABW',
			'numeric'  => '533',
			'currency' => array(
				'AWG',
			),
		),
		array(
			'name'     => 'Australia',
			'alpha2'   => 'AU',
			'alpha3'   => 'AUS',
			'numeric'  => '036',
			'currency' => array(
				'AUD',
			),
		),
		array(
			'name'     => 'Austria',
			'alpha2'   => 'AT',
			'alpha3'   => 'AUT',
			'numeric'  => '040',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Azerbaijan',
			'alpha2'   => 'AZ',
			'alpha3'   => 'AZE',
			'numeric'  => '031',
			'currency' => array(
				'AZN',
			),
		),
		array(
			'name'     => 'Bahamas',
			'alpha2'   => 'BS',
			'alpha3'   => 'BHS',
			'numeric'  => '044',
			'currency' => array(
				'BSD',
			),
		),
		array(
			'name'     => 'Bahrain',
			'alpha2'   => 'BH',
			'alpha3'   => 'BHR',
			'numeric'  => '048',
			'currency' => array(
				'BHD',
			),
		),
		array(
			'name'     => 'Bangladesh',
			'alpha2'   => 'BD',
			'alpha3'   => 'BGD',
			'numeric'  => '050',
			'currency' => array(
				'BDT',
			),
		),
		array(
			'name'     => 'Barbados',
			'alpha2'   => 'BB',
			'alpha3'   => 'BRB',
			'numeric'  => '052',
			'currency' => array(
				'BBD',
			),
		),
		array(
			'name'     => 'Belarus',
			'alpha2'   => 'BY',
			'alpha3'   => 'BLR',
			'numeric'  => '112',
			'currency' => array(
				'BYN',
			),
		),
		array(
			'name'     => 'Belgium',
			'alpha2'   => 'BE',
			'alpha3'   => 'BEL',
			'numeric'  => '056',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Belize',
			'alpha2'   => 'BZ',
			'alpha3'   => 'BLZ',
			'numeric'  => '084',
			'currency' => array(
				'BZD',
			),
		),
		array(
			'name'     => 'Benin',
			'alpha2'   => 'BJ',
			'alpha3'   => 'BEN',
			'numeric'  => '204',
			'currency' => array(
				'XOF',
			),
		),
		array(
			'name'     => 'Bermuda',
			'alpha2'   => 'BM',
			'alpha3'   => 'BMU',
			'numeric'  => '060',
			'currency' => array(
				'BMD',
			),
		),
		array(
			'name'     => 'Bhutan',
			'alpha2'   => 'BT',
			'alpha3'   => 'BTN',
			'numeric'  => '064',
			'currency' => array(
				'BTN',
			),
		),
		array(
			'name'     => 'Bolivia (Plurinational State of)',
			'alpha2'   => 'BO',
			'alpha3'   => 'BOL',
			'numeric'  => '068',
			'currency' => array(
				'BOB',
			),
		),
		array(
			'name'     => 'Bonaire, Sint Eustatius and Saba',
			'alpha2'   => 'BQ',
			'alpha3'   => 'BES',
			'numeric'  => '535',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Bosnia and Herzegovina',
			'alpha2'   => 'BA',
			'alpha3'   => 'BIH',
			'numeric'  => '070',
			'currency' => array(
				'BAM',
			),
		),
		array(
			'name'     => 'Botswana',
			'alpha2'   => 'BW',
			'alpha3'   => 'BWA',
			'numeric'  => '072',
			'currency' => array(
				'BWP',
			),
		),
		array(
			'name'     => 'Bouvet Island',
			'alpha2'   => 'BV',
			'alpha3'   => 'BVT',
			'numeric'  => '074',
			'currency' => array(
				'NOK',
			),
		),
		array(
			'name'     => 'Brazil',
			'alpha2'   => 'BR',
			'alpha3'   => 'BRA',
			'numeric'  => '076',
			'currency' => array(
				'BRL',
			),
		),
		array(
			'name'     => 'British Indian Ocean Territory',
			'alpha2'   => 'IO',
			'alpha3'   => 'IOT',
			'numeric'  => '086',
			'currency' => array(
				'GBP',
			),
		),
		array(
			'name'     => 'Brunei Darussalam',
			'alpha2'   => 'BN',
			'alpha3'   => 'BRN',
			'numeric'  => '096',
			'currency' => array(
				'BND',
				'SGD',
			),
		),
		array(
			'name'     => 'Bulgaria',
			'alpha2'   => 'BG',
			'alpha3'   => 'BGR',
			'numeric'  => '100',
			'currency' => array(
				'BGN',
			),
		),
		array(
			'name'     => 'Burkina Faso',
			'alpha2'   => 'BF',
			'alpha3'   => 'BFA',
			'numeric'  => '854',
			'currency' => array(
				'XOF',
			),
		),
		array(
			'name'     => 'Burundi',
			'alpha2'   => 'BI',
			'alpha3'   => 'BDI',
			'numeric'  => '108',
			'currency' => array(
				'BIF',
			),
		),
		array(
			'name'     => 'Cabo Verde',
			'alpha2'   => 'CV',
			'alpha3'   => 'CPV',
			'numeric'  => '132',
			'currency' => array(
				'CVE',
			),
		),
		array(
			'name'     => 'Cambodia',
			'alpha2'   => 'KH',
			'alpha3'   => 'KHM',
			'numeric'  => '116',
			'currency' => array(
				'KHR',
			),
		),
		array(
			'name'     => 'Cameroon',
			'alpha2'   => 'CM',
			'alpha3'   => 'CMR',
			'numeric'  => '120',
			'currency' => array(
				'XAF',
			),
		),
		array(
			'name'     => 'Canada',
			'alpha2'   => 'CA',
			'alpha3'   => 'CAN',
			'numeric'  => '124',
			'currency' => array(
				'CAD',
			),
		),
		array(
			'name'     => 'Cayman Islands',
			'alpha2'   => 'KY',
			'alpha3'   => 'CYM',
			'numeric'  => '136',
			'currency' => array(
				'KYD',
			),
		),
		array(
			'name'     => 'Central African Republic',
			'alpha2'   => 'CF',
			'alpha3'   => 'CAF',
			'numeric'  => '140',
			'currency' => array(
				'XAF',
			),
		),
		array(
			'name'     => 'Chad',
			'alpha2'   => 'TD',
			'alpha3'   => 'TCD',
			'numeric'  => '148',
			'currency' => array(
				'XAF',
			),
		),
		array(
			'name'     => 'Chile',
			'alpha2'   => 'CL',
			'alpha3'   => 'CHL',
			'numeric'  => '152',
			'currency' => array(
				'CLP',
			),
		),
		array(
			'name'     => 'China',
			'alpha2'   => 'CN',
			'alpha3'   => 'CHN',
			'numeric'  => '156',
			'currency' => array(
				'CNY',
			),
		),
		array(
			'name'     => 'Christmas Island',
			'alpha2'   => 'CX',
			'alpha3'   => 'CXR',
			'numeric'  => '162',
			'currency' => array(
				'AUD',
			),
		),
		array(
			'name'     => 'Cocos (Keeling) Islands',
			'alpha2'   => 'CC',
			'alpha3'   => 'CCK',
			'numeric'  => '166',
			'currency' => array(
				'AUD',
			),
		),
		array(
			'name'     => 'Colombia',
			'alpha2'   => 'CO',
			'alpha3'   => 'COL',
			'numeric'  => '170',
			'currency' => array(
				'COP',
			),
		),
		array(
			'name'     => 'Comoros',
			'alpha2'   => 'KM',
			'alpha3'   => 'COM',
			'numeric'  => '174',
			'currency' => array(
				'KMF',
			),
		),
		array(
			'name'     => 'Congo',
			'alpha2'   => 'CG',
			'alpha3'   => 'COG',
			'numeric'  => '178',
			'currency' => array(
				'XAF',
			),
		),
		array(
			'name'     => 'Congo (Democratic Republic of the)',
			'alpha2'   => 'CD',
			'alpha3'   => 'COD',
			'numeric'  => '180',
			'currency' => array(
				'CDF',
			),
		),
		array(
			'name'     => 'Cook Islands',
			'alpha2'   => 'CK',
			'alpha3'   => 'COK',
			'numeric'  => '184',
			'currency' => array(
				'NZD',
			),
		),
		array(
			'name'     => 'Costa Rica',
			'alpha2'   => 'CR',
			'alpha3'   => 'CRI',
			'numeric'  => '188',
			'currency' => array(
				'CRC',
			),
		),
		array(
			'name'     => 'Côte d\'Ivoire',
			'alpha2'   => 'CI',
			'alpha3'   => 'CIV',
			'numeric'  => '384',
			'currency' => array(
				'XOF',
			),
		),
		array(
			'name'     => 'Croatia',
			'alpha2'   => 'HR',
			'alpha3'   => 'HRV',
			'numeric'  => '191',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Cuba',
			'alpha2'   => 'CU',
			'alpha3'   => 'CUB',
			'numeric'  => '192',
			'currency' => array(
				'CUC',
				'CUP',
			),
		),
		array(
			'name'     => 'Curaçao',
			'alpha2'   => 'CW',
			'alpha3'   => 'CUW',
			'numeric'  => '531',
			'currency' => array(
				'ANG',
			),
		),
		array(
			'name'     => 'Cyprus',
			'alpha2'   => 'CY',
			'alpha3'   => 'CYP',
			'numeric'  => '196',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Czechia',
			'alpha2'   => 'CZ',
			'alpha3'   => 'CZE',
			'numeric'  => '203',
			'currency' => array(
				'CZK',
			),
		),
		array(
			'name'     => 'Denmark',
			'alpha2'   => 'DK',
			'alpha3'   => 'DNK',
			'numeric'  => '208',
			'currency' => array(
				'DKK',
			),
		),
		array(
			'name'     => 'Djibouti',
			'alpha2'   => 'DJ',
			'alpha3'   => 'DJI',
			'numeric'  => '262',
			'currency' => array(
				'DJF',
			),
		),
		array(
			'name'     => 'Dominica',
			'alpha2'   => 'DM',
			'alpha3'   => 'DMA',
			'numeric'  => '212',
			'currency' => array(
				'XCD',
			),
		),
		array(
			'name'     => 'Dominican Republic',
			'alpha2'   => 'DO',
			'alpha3'   => 'DOM',
			'numeric'  => '214',
			'currency' => array(
				'DOP',
			),
		),
		array(
			'name'     => 'Ecuador',
			'alpha2'   => 'EC',
			'alpha3'   => 'ECU',
			'numeric'  => '218',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Egypt',
			'alpha2'   => 'EG',
			'alpha3'   => 'EGY',
			'numeric'  => '818',
			'currency' => array(
				'EGP',
			),
		),
		array(
			'name'     => 'El Salvador',
			'alpha2'   => 'SV',
			'alpha3'   => 'SLV',
			'numeric'  => '222',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Equatorial Guinea',
			'alpha2'   => 'GQ',
			'alpha3'   => 'GNQ',
			'numeric'  => '226',
			'currency' => array(
				'XAF',
			),
		),
		array(
			'name'     => 'Eritrea',
			'alpha2'   => 'ER',
			'alpha3'   => 'ERI',
			'numeric'  => '232',
			'currency' => array(
				'ERN',
			),
		),
		array(
			'name'     => 'Estonia',
			'alpha2'   => 'EE',
			'alpha3'   => 'EST',
			'numeric'  => '233',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Ethiopia',
			'alpha2'   => 'ET',
			'alpha3'   => 'ETH',
			'numeric'  => '231',
			'currency' => array(
				'ETB',
			),
		),
		array(
			'name'     => 'Eswatini',
			'alpha2'   => 'SZ',
			'alpha3'   => 'SWZ',
			'numeric'  => '748',
			'currency' => array(
				'SZL',
				'ZAR',
			),
		),
		array(
			'name'     => 'Falkland Islands (Malvinas)',
			'alpha2'   => 'FK',
			'alpha3'   => 'FLK',
			'numeric'  => '238',
			'currency' => array(
				'FKP',
			),
		),
		array(
			'name'     => 'Faroe Islands',
			'alpha2'   => 'FO',
			'alpha3'   => 'FRO',
			'numeric'  => '234',
			'currency' => array(
				'DKK',
			),
		),
		array(
			'name'     => 'Fiji',
			'alpha2'   => 'FJ',
			'alpha3'   => 'FJI',
			'numeric'  => '242',
			'currency' => array(
				'FJD',
			),
		),
		array(
			'name'     => 'Finland',
			'alpha2'   => 'FI',
			'alpha3'   => 'FIN',
			'numeric'  => '246',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'France',
			'alpha2'   => 'FR',
			'alpha3'   => 'FRA',
			'numeric'  => '250',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'French Guiana',
			'alpha2'   => 'GF',
			'alpha3'   => 'GUF',
			'numeric'  => '254',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'French Polynesia',
			'alpha2'   => 'PF',
			'alpha3'   => 'PYF',
			'numeric'  => '258',
			'currency' => array(
				'XPF',
			),
		),
		array(
			'name'     => 'French Southern Territories',
			'alpha2'   => 'TF',
			'alpha3'   => 'ATF',
			'numeric'  => '260',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Gabon',
			'alpha2'   => 'GA',
			'alpha3'   => 'GAB',
			'numeric'  => '266',
			'currency' => array(
				'XAF',
			),
		),
		array(
			'name'     => 'Gambia',
			'alpha2'   => 'GM',
			'alpha3'   => 'GMB',
			'numeric'  => '270',
			'currency' => array(
				'GMD',
			),
		),
		array(
			'name'     => 'Georgia',
			'alpha2'   => 'GE',
			'alpha3'   => 'GEO',
			'numeric'  => '268',
			'currency' => array(
				'GEL',
			),
		),
		array(
			'name'     => 'Germany',
			'alpha2'   => 'DE',
			'alpha3'   => 'DEU',
			'numeric'  => '276',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Ghana',
			'alpha2'   => 'GH',
			'alpha3'   => 'GHA',
			'numeric'  => '288',
			'currency' => array(
				'GHS',
			),
		),
		array(
			'name'     => 'Gibraltar',
			'alpha2'   => 'GI',
			'alpha3'   => 'GIB',
			'numeric'  => '292',
			'currency' => array(
				'GIP',
			),
		),
		array(
			'name'     => 'Greece',
			'alpha2'   => 'GR',
			'alpha3'   => 'GRC',
			'numeric'  => '300',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Greenland',
			'alpha2'   => 'GL',
			'alpha3'   => 'GRL',
			'numeric'  => '304',
			'currency' => array(
				'DKK',
			),
		),
		array(
			'name'     => 'Grenada',
			'alpha2'   => 'GD',
			'alpha3'   => 'GRD',
			'numeric'  => '308',
			'currency' => array(
				'XCD',
			),
		),
		array(
			'name'     => 'Guadeloupe',
			'alpha2'   => 'GP',
			'alpha3'   => 'GLP',
			'numeric'  => '312',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Guam',
			'alpha2'   => 'GU',
			'alpha3'   => 'GUM',
			'numeric'  => '316',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Guatemala',
			'alpha2'   => 'GT',
			'alpha3'   => 'GTM',
			'numeric'  => '320',
			'currency' => array(
				'GTQ',
			),
		),
		array(
			'name'     => 'Guernsey',
			'alpha2'   => 'GG',
			'alpha3'   => 'GGY',
			'numeric'  => '831',
			'currency' => array(
				'GBP',
			),
		),
		array(
			'name'     => 'Guinea',
			'alpha2'   => 'GN',
			'alpha3'   => 'GIN',
			'numeric'  => '324',
			'currency' => array(
				'GNF',
			),
		),
		array(
			'name'     => 'Guinea-Bissau',
			'alpha2'   => 'GW',
			'alpha3'   => 'GNB',
			'numeric'  => '624',
			'currency' => array(
				'XOF',
			),
		),
		array(
			'name'     => 'Guyana',
			'alpha2'   => 'GY',
			'alpha3'   => 'GUY',
			'numeric'  => '328',
			'currency' => array(
				'GYD',
			),
		),
		array(
			'name'     => 'Haiti',
			'alpha2'   => 'HT',
			'alpha3'   => 'HTI',
			'numeric'  => '332',
			'currency' => array(
				'HTG',
			),
		),
		array(
			'name'     => 'Heard Island and McDonald Islands',
			'alpha2'   => 'HM',
			'alpha3'   => 'HMD',
			'numeric'  => '334',
			'currency' => array(
				'AUD',
			),
		),
		array(
			'name'     => 'Holy See',
			'alpha2'   => 'VA',
			'alpha3'   => 'VAT',
			'numeric'  => '336',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Honduras',
			'alpha2'   => 'HN',
			'alpha3'   => 'HND',
			'numeric'  => '340',
			'currency' => array(
				'HNL',
			),
		),
		array(
			'name'     => 'Hong Kong',
			'alpha2'   => 'HK',
			'alpha3'   => 'HKG',
			'numeric'  => '344',
			'currency' => array(
				'HKD',
			),
		),
		array(
			'name'     => 'Hungary',
			'alpha2'   => 'HU',
			'alpha3'   => 'HUN',
			'numeric'  => '348',
			'currency' => array(
				'HUF',
			),
		),
		array(
			'name'     => 'Iceland',
			'alpha2'   => 'IS',
			'alpha3'   => 'ISL',
			'numeric'  => '352',
			'currency' => array(
				'ISK',
			),
		),
		array(
			'name'     => 'India',
			'alpha2'   => 'IN',
			'alpha3'   => 'IND',
			'numeric'  => '356',
			'currency' => array(
				'INR',
			),
		),
		array(
			'name'     => 'Indonesia',
			'alpha2'   => 'ID',
			'alpha3'   => 'IDN',
			'numeric'  => '360',
			'currency' => array(
				'IDR',
			),
		),
		array(
			'name'     => 'Iran (Islamic Republic of)',
			'alpha2'   => 'IR',
			'alpha3'   => 'IRN',
			'numeric'  => '364',
			'currency' => array(
				'IRR',
			),
		),
		array(
			'name'     => 'Iraq',
			'alpha2'   => 'IQ',
			'alpha3'   => 'IRQ',
			'numeric'  => '368',
			'currency' => array(
				'IQD',
			),
		),
		array(
			'name'     => 'Ireland',
			'alpha2'   => 'IE',
			'alpha3'   => 'IRL',
			'numeric'  => '372',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Isle of Man',
			'alpha2'   => 'IM',
			'alpha3'   => 'IMN',
			'numeric'  => '833',
			'currency' => array(
				'GBP',
			),
		),
		array(
			'name'     => 'Israel',
			'alpha2'   => 'IL',
			'alpha3'   => 'ISR',
			'numeric'  => '376',
			'currency' => array(
				'ILS',
			),
		),
		array(
			'name'     => 'Italy',
			'alpha2'   => 'IT',
			'alpha3'   => 'ITA',
			'numeric'  => '380',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Jamaica',
			'alpha2'   => 'JM',
			'alpha3'   => 'JAM',
			'numeric'  => '388',
			'currency' => array(
				'JMD',
			),
		),
		array(
			'name'     => 'Japan',
			'alpha2'   => 'JP',
			'alpha3'   => 'JPN',
			'numeric'  => '392',
			'currency' => array(
				'JPY',
			),
		),
		array(
			'name'     => 'Jersey',
			'alpha2'   => 'JE',
			'alpha3'   => 'JEY',
			'numeric'  => '832',
			'currency' => array(
				'GBP',
			),
		),
		array(
			'name'     => 'Jordan',
			'alpha2'   => 'JO',
			'alpha3'   => 'JOR',
			'numeric'  => '400',
			'currency' => array(
				'JOD',
			),
		),
		array(
			'name'     => 'Kazakhstan',
			'alpha2'   => 'KZ',
			'alpha3'   => 'KAZ',
			'numeric'  => '398',
			'currency' => array(
				'KZT',
			),
		),
		array(
			'name'     => 'Kenya',
			'alpha2'   => 'KE',
			'alpha3'   => 'KEN',
			'numeric'  => '404',
			'currency' => array(
				'KES',
			),
		),
		array(
			'name'     => 'Kiribati',
			'alpha2'   => 'KI',
			'alpha3'   => 'KIR',
			'numeric'  => '296',
			'currency' => array(
				'AUD',
			),
		),
		array(
			'name'     => 'Korea (Democratic People\'s Republic of)',
			'alpha2'   => 'KP',
			'alpha3'   => 'PRK',
			'numeric'  => '408',
			'currency' => array(
				'KPW',
			),
		),
		array(
			'name'     => 'Korea (Republic of)',
			'alpha2'   => 'KR',
			'alpha3'   => 'KOR',
			'numeric'  => '410',
			'currency' => array(
				'KRW',
			),
		),
		array(
			'name'     => 'Kuwait',
			'alpha2'   => 'KW',
			'alpha3'   => 'KWT',
			'numeric'  => '414',
			'currency' => array(
				'KWD',
			),
		),
		array(
			'name'     => 'Kyrgyzstan',
			'alpha2'   => 'KG',
			'alpha3'   => 'KGZ',
			'numeric'  => '417',
			'currency' => array(
				'KGS',
			),
		),
		array(
			'name'     => 'Lao People\'s Democratic Republic',
			'alpha2'   => 'LA',
			'alpha3'   => 'LAO',
			'numeric'  => '418',
			'currency' => array(
				'LAK',
			),
		),
		array(
			'name'     => 'Latvia',
			'alpha2'   => 'LV',
			'alpha3'   => 'LVA',
			'numeric'  => '428',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Lebanon',
			'alpha2'   => 'LB',
			'alpha3'   => 'LBN',
			'numeric'  => '422',
			'currency' => array(
				'LBP',
			),
		),
		array(
			'name'     => 'Lesotho',
			'alpha2'   => 'LS',
			'alpha3'   => 'LSO',
			'numeric'  => '426',
			'currency' => array(
				'LSL',
				'ZAR',
			),
		),
		array(
			'name'     => 'Liberia',
			'alpha2'   => 'LR',
			'alpha3'   => 'LBR',
			'numeric'  => '430',
			'currency' => array(
				'LRD',
			),
		),
		array(
			'name'     => 'Libya',
			'alpha2'   => 'LY',
			'alpha3'   => 'LBY',
			'numeric'  => '434',
			'currency' => array(
				'LYD',
			),
		),
		array(
			'name'     => 'Liechtenstein',
			'alpha2'   => 'LI',
			'alpha3'   => 'LIE',
			'numeric'  => '438',
			'currency' => array(
				'CHF',
			),
		),
		array(
			'name'     => 'Lithuania',
			'alpha2'   => 'LT',
			'alpha3'   => 'LTU',
			'numeric'  => '440',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Luxembourg',
			'alpha2'   => 'LU',
			'alpha3'   => 'LUX',
			'numeric'  => '442',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Macao',
			'alpha2'   => 'MO',
			'alpha3'   => 'MAC',
			'numeric'  => '446',
			'currency' => array(
				'MOP',
			),
		),
		array(
			'name'     => 'North Macedonia',
			'alpha2'   => 'MK',
			'alpha3'   => 'MKD',
			'numeric'  => '807',
			'currency' => array(
				'MKD',
			),
		),
		array(
			'name'     => 'Madagascar',
			'alpha2'   => 'MG',
			'alpha3'   => 'MDG',
			'numeric'  => '450',
			'currency' => array(
				'MGA',
			),
		),
		array(
			'name'     => 'Malawi',
			'alpha2'   => 'MW',
			'alpha3'   => 'MWI',
			'numeric'  => '454',
			'currency' => array(
				'MWK',
			),
		),
		array(
			'name'     => 'Malaysia',
			'alpha2'   => 'MY',
			'alpha3'   => 'MYS',
			'numeric'  => '458',
			'currency' => array(
				'MYR',
			),
		),
		array(
			'name'     => 'Maldives',
			'alpha2'   => 'MV',
			'alpha3'   => 'MDV',
			'numeric'  => '462',
			'currency' => array(
				'MVR',
			),
		),
		array(
			'name'     => 'Mali',
			'alpha2'   => 'ML',
			'alpha3'   => 'MLI',
			'numeric'  => '466',
			'currency' => array(
				'XOF',
			),
		),
		array(
			'name'     => 'Malta',
			'alpha2'   => 'MT',
			'alpha3'   => 'MLT',
			'numeric'  => '470',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Marshall Islands',
			'alpha2'   => 'MH',
			'alpha3'   => 'MHL',
			'numeric'  => '584',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Martinique',
			'alpha2'   => 'MQ',
			'alpha3'   => 'MTQ',
			'numeric'  => '474',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Mauritania',
			'alpha2'   => 'MR',
			'alpha3'   => 'MRT',
			'numeric'  => '478',
			'currency' => array(
				'MRO',
			),
		),
		array(
			'name'     => 'Mauritius',
			'alpha2'   => 'MU',
			'alpha3'   => 'MUS',
			'numeric'  => '480',
			'currency' => array(
				'MUR',
			),
		),
		array(
			'name'     => 'Mayotte',
			'alpha2'   => 'YT',
			'alpha3'   => 'MYT',
			'numeric'  => '175',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Mexico',
			'alpha2'   => 'MX',
			'alpha3'   => 'MEX',
			'numeric'  => '484',
			'currency' => array(
				'MXN',
			),
		),
		array(
			'name'     => 'Micronesia (Federated States of)',
			'alpha2'   => 'FM',
			'alpha3'   => 'FSM',
			'numeric'  => '583',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Moldova (Republic of)',
			'alpha2'   => 'MD',
			'alpha3'   => 'MDA',
			'numeric'  => '498',
			'currency' => array(
				'MDL',
			),
		),
		array(
			'name'     => 'Monaco',
			'alpha2'   => 'MC',
			'alpha3'   => 'MCO',
			'numeric'  => '492',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Mongolia',
			'alpha2'   => 'MN',
			'alpha3'   => 'MNG',
			'numeric'  => '496',
			'currency' => array(
				'MNT',
			),
		),
		array(
			'name'     => 'Montenegro',
			'alpha2'   => 'ME',
			'alpha3'   => 'MNE',
			'numeric'  => '499',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Montserrat',
			'alpha2'   => 'MS',
			'alpha3'   => 'MSR',
			'numeric'  => '500',
			'currency' => array(
				'XCD',
			),
		),
		array(
			'name'     => 'Morocco',
			'alpha2'   => 'MA',
			'alpha3'   => 'MAR',
			'numeric'  => '504',
			'currency' => array(
				'MAD',
			),
		),
		array(
			'name'     => 'Mozambique',
			'alpha2'   => 'MZ',
			'alpha3'   => 'MOZ',
			'numeric'  => '508',
			'currency' => array(
				'MZN',
			),
		),
		array(
			'name'     => 'Myanmar',
			'alpha2'   => 'MM',
			'alpha3'   => 'MMR',
			'numeric'  => '104',
			'currency' => array(
				'MMK',
			),
		),
		array(
			'name'     => 'Namibia',
			'alpha2'   => 'NA',
			'alpha3'   => 'NAM',
			'numeric'  => '516',
			'currency' => array(
				'NAD',
				'ZAR',
			),
		),
		array(
			'name'     => 'Nauru',
			'alpha2'   => 'NR',
			'alpha3'   => 'NRU',
			'numeric'  => '520',
			'currency' => array(
				'AUD',
			),
		),
		array(
			'name'     => 'Nepal',
			'alpha2'   => 'NP',
			'alpha3'   => 'NPL',
			'numeric'  => '524',
			'currency' => array(
				'NPR',
			),
		),
		array(
			'name'     => 'Netherlands',
			'alpha2'   => 'NL',
			'alpha3'   => 'NLD',
			'numeric'  => '528',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'New Caledonia',
			'alpha2'   => 'NC',
			'alpha3'   => 'NCL',
			'numeric'  => '540',
			'currency' => array(
				'XPF',
			),
		),
		array(
			'name'     => 'New Zealand',
			'alpha2'   => 'NZ',
			'alpha3'   => 'NZL',
			'numeric'  => '554',
			'currency' => array(
				'NZD',
			),
		),
		array(
			'name'     => 'Nicaragua',
			'alpha2'   => 'NI',
			'alpha3'   => 'NIC',
			'numeric'  => '558',
			'currency' => array(
				'NIO',
			),
		),
		array(
			'name'     => 'Niger',
			'alpha2'   => 'NE',
			'alpha3'   => 'NER',
			'numeric'  => '562',
			'currency' => array(
				'XOF',
			),
		),
		array(
			'name'     => 'Nigeria',
			'alpha2'   => 'NG',
			'alpha3'   => 'NGA',
			'numeric'  => '566',
			'currency' => array(
				'NGN',
			),
		),
		array(
			'name'     => 'Niue',
			'alpha2'   => 'NU',
			'alpha3'   => 'NIU',
			'numeric'  => '570',
			'currency' => array(
				'NZD',
			),
		),
		array(
			'name'     => 'Norfolk Island',
			'alpha2'   => 'NF',
			'alpha3'   => 'NFK',
			'numeric'  => '574',
			'currency' => array(
				'AUD',
			),
		),
		array(
			'name'     => 'Northern Mariana Islands',
			'alpha2'   => 'MP',
			'alpha3'   => 'MNP',
			'numeric'  => '580',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Norway',
			'alpha2'   => 'NO',
			'alpha3'   => 'NOR',
			'numeric'  => '578',
			'currency' => array(
				'NOK',
			),
		),
		array(
			'name'     => 'Oman',
			'alpha2'   => 'OM',
			'alpha3'   => 'OMN',
			'numeric'  => '512',
			'currency' => array(
				'OMR',
			),
		),
		array(
			'name'     => 'Pakistan',
			'alpha2'   => 'PK',
			'alpha3'   => 'PAK',
			'numeric'  => '586',
			'currency' => array(
				'PKR',
			),
		),
		array(
			'name'     => 'Palau',
			'alpha2'   => 'PW',
			'alpha3'   => 'PLW',
			'numeric'  => '585',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Palestine, State of',
			'alpha2'   => 'PS',
			'alpha3'   => 'PSE',
			'numeric'  => '275',
			'currency' => array(
				'ILS',
			),
		),
		array(
			'name'     => 'Panama',
			'alpha2'   => 'PA',
			'alpha3'   => 'PAN',
			'numeric'  => '591',
			'currency' => array(
				'PAB',
			),
		),
		array(
			'name'     => 'Papua New Guinea',
			'alpha2'   => 'PG',
			'alpha3'   => 'PNG',
			'numeric'  => '598',
			'currency' => array(
				'PGK',
			),
		),
		array(
			'name'     => 'Paraguay',
			'alpha2'   => 'PY',
			'alpha3'   => 'PRY',
			'numeric'  => '600',
			'currency' => array(
				'PYG',
			),
		),
		array(
			'name'     => 'Peru',
			'alpha2'   => 'PE',
			'alpha3'   => 'PER',
			'numeric'  => '604',
			'currency' => array(
				'PEN',
			),
		),
		array(
			'name'     => 'Philippines',
			'alpha2'   => 'PH',
			'alpha3'   => 'PHL',
			'numeric'  => '608',
			'currency' => array(
				'PHP',
			),
		),
		array(
			'name'     => 'Pitcairn',
			'alpha2'   => 'PN',
			'alpha3'   => 'PCN',
			'numeric'  => '612',
			'currency' => array(
				'NZD',
			),
		),
		array(
			'name'     => 'Poland',
			'alpha2'   => 'PL',
			'alpha3'   => 'POL',
			'numeric'  => '616',
			'currency' => array(
				'PLN',
			),
		),
		array(
			'name'     => 'Portugal',
			'alpha2'   => 'PT',
			'alpha3'   => 'PRT',
			'numeric'  => '620',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Puerto Rico',
			'alpha2'   => 'PR',
			'alpha3'   => 'PRI',
			'numeric'  => '630',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Qatar',
			'alpha2'   => 'QA',
			'alpha3'   => 'QAT',
			'numeric'  => '634',
			'currency' => array(
				'QAR',
			),
		),
		array(
			'name'     => 'Réunion',
			'alpha2'   => 'RE',
			'alpha3'   => 'REU',
			'numeric'  => '638',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Romania',
			'alpha2'   => 'RO',
			'alpha3'   => 'ROU',
			'numeric'  => '642',
			'currency' => array(
				'RON',
			),
		),
		array(
			'name'     => 'Russian Federation',
			'alpha2'   => 'RU',
			'alpha3'   => 'RUS',
			'numeric'  => '643',
			'currency' => array(
				'RUB',
			),
		),
		array(
			'name'     => 'Rwanda',
			'alpha2'   => 'RW',
			'alpha3'   => 'RWA',
			'numeric'  => '646',
			'currency' => array(
				'RWF',
			),
		),
		array(
			'name'     => 'Saint Barthélemy',
			'alpha2'   => 'BL',
			'alpha3'   => 'BLM',
			'numeric'  => '652',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Saint Helena, Ascension and Tristan da Cunha',
			'alpha2'   => 'SH',
			'alpha3'   => 'SHN',
			'numeric'  => '654',
			'currency' => array(
				'SHP',
			),
		),
		array(
			'name'     => 'Saint Kitts and Nevis',
			'alpha2'   => 'KN',
			'alpha3'   => 'KNA',
			'numeric'  => '659',
			'currency' => array(
				'XCD',
			),
		),
		array(
			'name'     => 'Saint Lucia',
			'alpha2'   => 'LC',
			'alpha3'   => 'LCA',
			'numeric'  => '662',
			'currency' => array(
				'XCD',
			),
		),
		array(
			'name'     => 'Saint Martin (French part)',
			'alpha2'   => 'MF',
			'alpha3'   => 'MAF',
			'numeric'  => '663',
			'currency' => array(
				'EUR',
				'USD',
			),
		),
		array(
			'name'     => 'Saint Pierre and Miquelon',
			'alpha2'   => 'PM',
			'alpha3'   => 'SPM',
			'numeric'  => '666',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Saint Vincent and the Grenadines',
			'alpha2'   => 'VC',
			'alpha3'   => 'VCT',
			'numeric'  => '670',
			'currency' => array(
				'XCD',
			),
		),
		array(
			'name'     => 'Samoa',
			'alpha2'   => 'WS',
			'alpha3'   => 'WSM',
			'numeric'  => '882',
			'currency' => array(
				'WST',
			),
		),
		array(
			'name'     => 'San Marino',
			'alpha2'   => 'SM',
			'alpha3'   => 'SMR',
			'numeric'  => '674',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Sao Tome and Principe',
			'alpha2'   => 'ST',
			'alpha3'   => 'STP',
			'numeric'  => '678',
			'currency' => array(
				'STD',
			),
		),
		array(
			'name'     => 'Saudi Arabia',
			'alpha2'   => 'SA',
			'alpha3'   => 'SAU',
			'numeric'  => '682',
			'currency' => array(
				'SAR',
			),
		),
		array(
			'name'     => 'Senegal',
			'alpha2'   => 'SN',
			'alpha3'   => 'SEN',
			'numeric'  => '686',
			'currency' => array(
				'XOF',
			),
		),
		array(
			'name'     => 'Serbia',
			'alpha2'   => 'RS',
			'alpha3'   => 'SRB',
			'numeric'  => '688',
			'currency' => array(
				'RSD',
			),
		),
		array(
			'name'     => 'Seychelles',
			'alpha2'   => 'SC',
			'alpha3'   => 'SYC',
			'numeric'  => '690',
			'currency' => array(
				'SCR',
			),
		),
		array(
			'name'     => 'Sierra Leone',
			'alpha2'   => 'SL',
			'alpha3'   => 'SLE',
			'numeric'  => '694',
			'currency' => array(
				'SLL',
			),
		),
		array(
			'name'     => 'Singapore',
			'alpha2'   => 'SG',
			'alpha3'   => 'SGP',
			'numeric'  => '702',
			'currency' => array(
				'SGD',
			),
		),
		array(
			'name'     => 'Sint Maarten (Dutch part)',
			'alpha2'   => 'SX',
			'alpha3'   => 'SXM',
			'numeric'  => '534',
			'currency' => array(
				'ANG',
			),
		),
		array(
			'name'     => 'Slovakia',
			'alpha2'   => 'SK',
			'alpha3'   => 'SVK',
			'numeric'  => '703',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Slovenia',
			'alpha2'   => 'SI',
			'alpha3'   => 'SVN',
			'numeric'  => '705',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Solomon Islands',
			'alpha2'   => 'SB',
			'alpha3'   => 'SLB',
			'numeric'  => '090',
			'currency' => array(
				'SBD',
			),
		),
		array(
			'name'     => 'Somalia',
			'alpha2'   => 'SO',
			'alpha3'   => 'SOM',
			'numeric'  => '706',
			'currency' => array(
				'SOS',
			),
		),
		array(
			'name'     => 'South Africa',
			'alpha2'   => 'ZA',
			'alpha3'   => 'ZAF',
			'numeric'  => '710',
			'currency' => array(
				'ZAR',
			),
		),
		array(
			'name'     => 'South Georgia and the South Sandwich Islands',
			'alpha2'   => 'GS',
			'alpha3'   => 'SGS',
			'numeric'  => '239',
			'currency' => array(
				'GBP',
			),
		),
		array(
			'name'     => 'South Sudan',
			'alpha2'   => 'SS',
			'alpha3'   => 'SSD',
			'numeric'  => '728',
			'currency' => array(
				'SSP',
			),
		),
		array(
			'name'     => 'Spain',
			'alpha2'   => 'ES',
			'alpha3'   => 'ESP',
			'numeric'  => '724',
			'currency' => array(
				'EUR',
			),
		),
		array(
			'name'     => 'Sri Lanka',
			'alpha2'   => 'LK',
			'alpha3'   => 'LKA',
			'numeric'  => '144',
			'currency' => array(
				'LKR',
			),
		),
		array(
			'name'     => 'Sudan',
			'alpha2'   => 'SD',
			'alpha3'   => 'SDN',
			'numeric'  => '729',
			'currency' => array(
				'SDG',
			),
		),
		array(
			'name'     => 'Suriname',
			'alpha2'   => 'SR',
			'alpha3'   => 'SUR',
			'numeric'  => '740',
			'currency' => array(
				'SRD',
			),
		),
		array(
			'name'     => 'Svalbard and Jan Mayen',
			'alpha2'   => 'SJ',
			'alpha3'   => 'SJM',
			'numeric'  => '744',
			'currency' => array(
				'NOK',
			),
		),
		array(
			'name'     => 'Sweden',
			'alpha2'   => 'SE',
			'alpha3'   => 'SWE',
			'numeric'  => '752',
			'currency' => array(
				'SEK',
			),
		),
		array(
			'name'     => 'Switzerland',
			'alpha2'   => 'CH',
			'alpha3'   => 'CHE',
			'numeric'  => '756',
			'currency' => array(
				'CHF',
			),
		),
		array(
			'name'     => 'Syrian Arab Republic',
			'alpha2'   => 'SY',
			'alpha3'   => 'SYR',
			'numeric'  => '760',
			'currency' => array(
				'SYP',
			),
		),
		array(
			'name'     => 'Taiwan (Province of China)',
			'alpha2'   => 'TW',
			'alpha3'   => 'TWN',
			'numeric'  => '158',
			'currency' => array(
				'TWD',
			),
		),
		array(
			'name'     => 'Tajikistan',
			'alpha2'   => 'TJ',
			'alpha3'   => 'TJK',
			'numeric'  => '762',
			'currency' => array(
				'TJS',
			),
		),
		array(
			'name'     => 'Tanzania, United Republic of',
			'alpha2'   => 'TZ',
			'alpha3'   => 'TZA',
			'numeric'  => '834',
			'currency' => array(
				'TZS',
			),
		),
		array(
			'name'     => 'Thailand',
			'alpha2'   => 'TH',
			'alpha3'   => 'THA',
			'numeric'  => '764',
			'currency' => array(
				'THB',
			),
		),
		array(
			'name'     => 'Timor-Leste',
			'alpha2'   => 'TL',
			'alpha3'   => 'TLS',
			'numeric'  => '626',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Togo',
			'alpha2'   => 'TG',
			'alpha3'   => 'TGO',
			'numeric'  => '768',
			'currency' => array(
				'XOF',
			),
		),
		array(
			'name'     => 'Tokelau',
			'alpha2'   => 'TK',
			'alpha3'   => 'TKL',
			'numeric'  => '772',
			'currency' => array(
				'NZD',
			),
		),
		array(
			'name'     => 'Tonga',
			'alpha2'   => 'TO',
			'alpha3'   => 'TON',
			'numeric'  => '776',
			'currency' => array(
				'TOP',
			),
		),
		array(
			'name'     => 'Trinidad and Tobago',
			'alpha2'   => 'TT',
			'alpha3'   => 'TTO',
			'numeric'  => '780',
			'currency' => array(
				'TTD',
			),
		),
		array(
			'name'     => 'Tunisia',
			'alpha2'   => 'TN',
			'alpha3'   => 'TUN',
			'numeric'  => '788',
			'currency' => array(
				'TND',
			),
		),
		array(
			'name'     => 'Turkey',
			'alpha2'   => 'TR',
			'alpha3'   => 'TUR',
			'numeric'  => '792',
			'currency' => array(
				'TRY',
			),
		),
		array(
			'name'     => 'Turkmenistan',
			'alpha2'   => 'TM',
			'alpha3'   => 'TKM',
			'numeric'  => '795',
			'currency' => array(
				'TMT',
			),
		),
		array(
			'name'     => 'Turks and Caicos Islands',
			'alpha2'   => 'TC',
			'alpha3'   => 'TCA',
			'numeric'  => '796',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Tuvalu',
			'alpha2'   => 'TV',
			'alpha3'   => 'TUV',
			'numeric'  => '798',
			'currency' => array(
				'AUD',
			),
		),
		array(
			'name'     => 'Uganda',
			'alpha2'   => 'UG',
			'alpha3'   => 'UGA',
			'numeric'  => '800',
			'currency' => array(
				'UGX',
			),
		),
		array(
			'name'     => 'Ukraine',
			'alpha2'   => 'UA',
			'alpha3'   => 'UKR',
			'numeric'  => '804',
			'currency' => array(
				'UAH',
			),
		),
		array(
			'name'     => 'United Arab Emirates',
			'alpha2'   => 'AE',
			'alpha3'   => 'ARE',
			'numeric'  => '784',
			'currency' => array(
				'AED',
			),
		),
		array(
			'name'     => 'United Kingdom of Great Britain and Northern Ireland',
			'alpha2'   => 'GB',
			'alpha3'   => 'GBR',
			'numeric'  => '826',
			'currency' => array(
				'GBP',
			),
		),
		array(
			'name'     => 'United States of America',
			'alpha2'   => 'US',
			'alpha3'   => 'USA',
			'numeric'  => '840',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'United States Minor Outlying Islands',
			'alpha2'   => 'UM',
			'alpha3'   => 'UMI',
			'numeric'  => '581',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Uruguay',
			'alpha2'   => 'UY',
			'alpha3'   => 'URY',
			'numeric'  => '858',
			'currency' => array(
				'UYU',
			),
		),
		array(
			'name'     => 'Uzbekistan',
			'alpha2'   => 'UZ',
			'alpha3'   => 'UZB',
			'numeric'  => '860',
			'currency' => array(
				'UZS',
			),
		),
		array(
			'name'     => 'Vanuatu',
			'alpha2'   => 'VU',
			'alpha3'   => 'VUT',
			'numeric'  => '548',
			'currency' => array(
				'VUV',
			),
		),
		array(
			'name'     => 'Venezuela (Bolivarian Republic of)',
			'alpha2'   => 'VE',
			'alpha3'   => 'VEN',
			'numeric'  => '862',
			'currency' => array(
				'VEF',
			),
		),
		array(
			'name'     => 'Viet Nam',
			'alpha2'   => 'VN',
			'alpha3'   => 'VNM',
			'numeric'  => '704',
			'currency' => array(
				'VND',
			),
		),
		array(
			'name'     => 'Virgin Islands (British)',
			'alpha2'   => 'VG',
			'alpha3'   => 'VGB',
			'numeric'  => '092',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Virgin Islands (U.S.)',
			'alpha2'   => 'VI',
			'alpha3'   => 'VIR',
			'numeric'  => '850',
			'currency' => array(
				'USD',
			),
		),
		array(
			'name'     => 'Wallis and Futuna',
			'alpha2'   => 'WF',
			'alpha3'   => 'WLF',
			'numeric'  => '876',
			'currency' => array(
				'XPF',
			),
		),
		array(
			'name'     => 'Western Sahara',
			'alpha2'   => 'EH',
			'alpha3'   => 'ESH',
			'numeric'  => '732',
			'currency' => array(
				'MAD',
			),
		),
		array(
			'name'     => 'Yemen',
			'alpha2'   => 'YE',
			'alpha3'   => 'YEM',
			'numeric'  => '887',
			'currency' => array(
				'YER',
			),
		),
		array(
			'name'     => 'Zambia',
			'alpha2'   => 'ZM',
			'alpha3'   => 'ZMB',
			'numeric'  => '894',
			'currency' => array(
				'ZMW',
			),
		),
		array(
			'name'     => 'Zimbabwe',
			'alpha2'   => 'ZW',
			'alpha3'   => 'ZWE',
			'numeric'  => '716',
			'currency' => array(
				'BWP',
				'EUR',
				'GBP',
				'USD',
				'ZAR',
			),
		),
	);

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 */
	public function init() {
	}

	/**
	 * Counts the number of countries in the list.
	 *
	 * @return int Number of countries.
	 */
	public function count(): int {
		return count( $this->countries );
	}

	/**
	 * Retrieves all country data.
	 *
	 * @return array List of countries with their details.
	 */
	public function all(): array {
		return $this->countries;
	}

	/**
	 * Finds a country by its exact name.
	 *
	 * @param string $name Name of the country to find.
	 * @return false Country data if not found, null otherwise.
	 */
	public function exactName( string $name ) {
		$value = mb_strtolower( $name );

		foreach ( $this->countries as $country ) {
			$comparison = mb_strtolower( $country['name'] );

			if ( $value === $comparison ) {
				return $country;
			}
		}
		return false;
	}

	/**
	 * Finds a country by its exact name.
	 *
	 * @param string $name Name of the country to find.
	 * @return false Country data if not found, null otherwise.
	 */
	public function getCountryByAlpha3( string $name ) {
		$value = mb_strtolower( $name );

		foreach ( $this->countries as $country ) {
			$comparison = mb_strtolower( $country['alpha3'] );

			if ( $value === $comparison ) {
				return $country;
			}
		}
		return false;
	}

	/**
	 * Lists all countries by their alpha-3 code and name.
	 *
	 * @return array List of countries with alpha-3 codes as keys and names as values.
	 */
	public function listCountries(): array {
		$list = array();
		foreach ( $this->countries as $country ) {
			$list[ $country['alpha3'] ] = $country['name'];
		}
		return $list;
	}
}
