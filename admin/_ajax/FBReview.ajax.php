<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_FBREVIEWS;

if (!APP_FBREVIEW || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'FBReview';
$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//VALIDA AÇÃO
if ($PostData && $PostData['callback_action'] && $PostData['callback'] == $CallBack):
    //PREPARA OS DADOS
    $Case = $PostData['callback_action'];
    unset($PostData['callback'], $PostData['callback_action']);

    // AUTO INSTANCE OBJECT READ
    if (empty($Read)):
        $Read = new Read;
    endif;

    // AUTO INSTANCE OBJECT CREATE
    if (empty($Create)):
        $Create = new Create;
    endif;

    // AUTO INSTANCE OBJECT UPDATE
    if (empty($Update)):
        $Update = new Update;
    endif;

    // AUTO INSTANCE OBJECT DELETE
    if (empty($Delete)):
        $Delete = new Delete;
    endif;

    //CLASSE FACEBOOK
    require_once '../../_app/Library/Facebook/autoload.php';

    $app_id = FBREVIEW_APP_ID;
    $app_secret = FBREVIEW_APP_SECRET;
    $fb = new \Facebook\Facebook(['app_id' => $app_id, 'app_secret' => $app_secret, 'default_graph_version' => 'v2.10',]);

    //SELECIONA AÇÃO
    switch ($Case):
        //PEGA REVIEWS
        case 'reviews':
            if (!empty($PostData['token']) && mb_strlen($PostData['token']) > 8):
                $page_id = FBREVIEW_PAGE_ID;
                $access_token = $PostData['token'];

                try {
                    // Retorna objeto com reviews
                    $response = $fb->get('/' . $page_id . '/?fields=ratings.limit(' . FBREVIEW_LIMIT . ')', $access_token);
                    $graphEdge = $response->getBody();

                    $obj = json_decode($graphEdge);

                    $Reviews = $obj->ratings->data;
                } catch (Facebook\Exceptions\FacebookResponseException $e) {
                    echo "<b>Graph Retornou:</b> " . $e->getMessage();
                    exit;
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                    echo "<b class='icon-warning'>Facebook SDK Retornou:</b>" . $e->getMessage();
                    exit;
                }

                $Read->ExeRead(DB_TESTIMONIALS, "WHERE testimonial_type = 2");
                if (!$Read->getResult()):
                    foreach ($Reviews AS $Review):
                        if (empty($Review->review_text)):
                            $CreateReviews = [
                                'fb_review_id' => $Review->reviewer->id,
                                'testimonial_name' => $Review->reviewer->name,
                                'testimonial_date' => $Review->created_time,
                                'testimonial_rating' => $Review->rating,
                                'testimonial_type' => 2
                            ];
                        else:
                            $CreateReviews = [
                                'fb_review_id' => $Review->reviewer->id,
                                'testimonial_name' => $Review->reviewer->name,
                                'testimonial_depoiment' => $Review->review_text,
                                'testimonial_rating' => $Review->rating,
                                'testimonial_date' => $Review->created_time,
                                'testimonial_type' => 2
                            ];
                        endif;

                        $Create->ExeCreate(DB_TESTIMONIALS, $CreateReviews);
                        
                        $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "Reviews do Facebook Importados Com Sucesso! <b>Aguarde...</b>"];
                        $jSON['redirect'] = 'dashboard.php?wc=fbreview/home';
                    endforeach;
                else:
                    $jSON['alert'] = ["yellow", "warning", "OPSSS", "Para Atualizar os Reviews do Facebook, Remova Todos!"];
                endif;
            else:
                $jSON['alert'] = ["red", "wondering2", "ERRO NO TOKEN", "Desculpe {$_SESSION['userLogin']['user_name']}, Mas o Access Token Parece Ser Inválido!"];
            endif;
            break;

        //REMOVE REVIEW
        case 'delete':
            $Read->ExeRead(DB_TESTIMONIALS);
            if ($Read->getResult()):
                foreach ($Read->getResult() AS $Review):
                    $Delete->ExeDelete(DB_TESTIMONIALS, "WHERE fb_review_id = :id", "id={$Review['fb_review_id']}");
                endforeach;
            endif;
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO", "Reviews do Facebook Removidos Com Sucesso! <b>Aguarde...</b>"];
            $jSON['redirect'] = 'dashboard.php?wc=fbreview/home';
            break;

    endswitch;

    //RETORNA O CALLBACK
    if ($jSON):
        echo json_encode($jSON);
    else:
        $jSON['alert'] = ["red", "wondering2", "Desculpe {$_SESSION['userLogin']['user_name']}", "Uma Ação Do Sistema Não Respondeu Corretamente. Ao Persistir, Contate o Desenvolvedor!"];
        echo json_encode($jSON);
    endif;
else:
    //ACESSO DIRETO
    die('<br><br><br><center><h1>Acesso Restrito!</h1></center>');
endif;
