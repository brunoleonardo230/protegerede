<?php
/******************************************
************* Funções Gerais **************
******************************************/

/*
 * Recupera Categorias das Galerias (APP Galeria de Imagens)
 */
function getGalleryCat($Category = null)
{
    $GalleryCat = [
        1 => 'Denthal',
        2 => 'Gastroentorology',
        3 => 'Surgeries',
        4 => 'Cardiology',
        5 => 'Patology',
    ];
    if (!empty($Category)):
        return $GalleryCat[$Category];
    else:
        return $GalleryCat;
    endif;
} 

/*
 * Categoria dos Tutoriais (APP Tutoriais)
 */
function getTutorialCat($Category = null)
{
    $TutorialCat = [
        1 => 'Tutoriais do Site',
        2 => 'Tutoriais do Sistema',
        3 => 'Tutoriais de Configurações',
        4 => 'Outros Tutoriais'
    ];
    if (!empty($Category)):
        return $TutorialCat[$Category];
    else:
        return $TutorialCat;
    endif;
} 

/*
 * Dias da Semana (Geral do Sistema)
 */
function getWeekDays($Days = null)
{
    $WeekDays = [
        1 => 'Segunda-Feira',
        2 => 'Terça-Feira',
        3 => 'Quarta-Feira',
        4 => 'Quinta-Feira',
        5 => 'Sexta-Feira',
        6 => 'Sábado',
        7 => 'Domingo'
    ];
    if (!empty($Days)):
        return $WeekDays[$Days];
    else:
        return $WeekDays;
    endif;
} 

/*
 * Meses do Ano (Geral do Sistema)
 */
function getMonthYear($Month = null)
{
    $MonthYear = [
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro'
    ];
    if (!empty($Month)):
        return $MonthYear[$Month];
    else:
        return $MonthYear;
    endif;
} 

/*
 * Anos (Geral do Sistema)
 */
function getYear($Year = null)
{
    $Years = [
        1 => '2019',
        2 => '2020',
        3 => '2021',
        4 => '2022',
        5 => '2023',
        6 => '2024',
        7 => '2025'
    ];
    if (!empty($Year)):
        return $Years[$Year];
    else:
        return $Years;
    endif;
} 

/*
 * Categoria das Logos
 */
function getLogoType($Type = null)
{
    $LogoType = [
        1 => 'Logo Para Fundo Claro',
        2 => 'Logo Para Fundo Escuro',
        3 => 'Logo Redonda',
        4 => 'Logo Quadrada'
    ];
    if (!empty($Type)):
        return $LogoType[$Type];
    else:
        return $LogoType;
    endif;
} 

/*
 * Categoria dos Favicon
 */
function getFaviconType($Type = null)
{
    $FaviconType = [
        1 => '16x16',
        2 => '32x32',
        3 => '96x96',
        4 => '128x128',
        5 => '512x512'
    ];
    if (!empty($Type)):
        return $FaviconType[$Type];
    else:
        return $FaviconType;
    endif;
} 

/******************************************
**************** Médicos ****************
******************************************/

/*
 * Especialidades dos Médicos (Cadastro de Médicos)
 */
function getSpecialtiesDoctors($Specialties = null)
{
    $SpecialtiesDoctors = [
        1 => 'Cardiologista',
        2 => 'Neurologista',
        3 => 'Pediatrician',
        4 => 'Pathologist',
    ];
    if (!empty($Specialties)):
        return $SpecialtiesDoctors[$Specialties];
    else:
        return $SpecialtiesDoctors;
    endif;
}

/*
 * Categorias dos Serviços (Cadastro de Serviços)
 */
function getServicesType($Type = null)
{
    $ServicesType = [
        1 => 'Bem Vindo (Home)',
        2 => 'O Que Oferecemos',
        3 => 'Outros Serviços',
        4 => 'Serviços Extras'
    ];
    if (!empty($Type)):
        return $ServicesType[$Type];
    else:
        return $ServicesType;
    endif;
}
