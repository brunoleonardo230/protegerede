<?php

session_start();
require '../../_app/Config.inc.php';
$NivelAcess = LEVEL_WC_POSTS;

if (!APP_POSTS || empty($_SESSION['userLogin']) || empty($_SESSION['userLogin']['user_level']) || $_SESSION['userLogin']['user_level'] < $NivelAcess):
    $jSON['alert'] = ["red", "wondering2", "OPSSS", "Você Não Tem Permissão Para Essa Ação ou Não Está Logado Como Administrador!"];
    echo json_encode($jSON);
    die;
endif;

usleep(50000);

//DEFINE O CALLBACK E RECUPERA O POST
$jSON = null;
$CallBack = 'Posts';
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

    //SELECIONA AÇÃO
    switch ($Case):
        //DELETE
        case 'delete':
            $PostData['post_id'] = $PostData['del_id'];
            $Read->FullRead("SELECT post_cover FROM " . DB_POSTS . " WHERE post_id = :ps", "ps={$PostData['post_id']}");
            if ($Read->getResult() && file_exists("../../uploads/{$Read->getResult()[0]['post_cover']}") && !is_dir("../../uploads/{$Read->getResult()[0]['post_cover']}")):
                unlink("../../uploads/{$Read->getResult()[0]['post_cover']}");
            endif;

            $Read->FullRead("SELECT image FROM " . DB_POSTS_IMAGE . " WHERE post_id = :ps", "ps={$PostData['post_id']}");
            if ($Read->getResult()):
                foreach ($Read->getResult() as $PostImage):
                    $ImageRemove = "../../uploads/{$PostImage['image']}";
                    if (file_exists($ImageRemove) && !is_dir($ImageRemove)):
                        unlink($ImageRemove);
                    endif;
                endforeach;
            endif;

            $Delete->ExeDelete(DB_POSTS, "WHERE post_id = :id", "id={$PostData['post_id']}");
            $Delete->ExeDelete(DB_POSTS_IMAGE, "WHERE post_id = :id", "id={$PostData['post_id']}");
            $Delete->ExeDelete(DB_COMMENTS, "WHERE post_id = :id", "id={$PostData['post_id']}");
            $jSON['success'] = true;
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Post Foi Excluído Com Sucesso!"];
            $jSON['redirect'] = "dashboard.php?wc=posts/home";
            break;

        case 'manager':
            $PostId = $PostData['post_id'];
            unset($PostData['post_id']);

            $Read->ExeRead(DB_POSTS, "WHERE post_id = :id", "id={$PostId}");
            $ThisPost = $Read->getResult()[0];

            $PostData['post_name'] = (!empty($PostData['post_name']) ? Check::Name($PostData['post_name']) : Check::Name($PostData['post_title']));
            $Read->ExeRead(DB_POSTS, "WHERE post_id != :id AND post_name = :name", "id={$PostId}&name={$PostData['post_name']}");
            if ($Read->getResult()):
                $PostData['post_name'] = "{$PostData['post_name']}-{$PostId}";
            endif;
            $jSON['name'] = $PostData['post_name'];

            if (!empty($_FILES['post_cover'])):
                $File = $_FILES['post_cover'];

                if ($ThisPost['post_cover'] && file_exists("../../uploads/{$ThisPost['post_cover']}") && !is_dir("../../uploads/{$ThisPost['post_cover']}")):
                    unlink("../../uploads/{$ThisPost['post_cover']}");
                endif;

                $Upload = new Upload('../../uploads/');
                $Upload->Image($File, $PostData['post_name'] . '-' . time(), IMAGE_W);
                if ($Upload->getResult()):
                    $PostData['post_cover'] = $Upload->getResult();
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Enviar Como Imagem!"];
                    echo json_encode($jSON);
                    return;
                endif;
            else:
                unset($PostData['post_cover']);
            endif;

            $PostData['post_status'] = (!empty($PostData['post_status']) ? '1' : '0');
            $PostData['post_date'] = (!empty($PostData['post_date']) ? Check::Data($PostData['post_date']) : date('Y-m-d H:i:s'));
            
            /* CATEGORIAS */
            if (isset($PostData['post_category_parent'])):
                $PostData['post_category'] = array();

                foreach ($PostData['post_category_parent'] as $CAT):
                    $Read->FullRead("SELECT category_parent FROM " . DB_CATEGORIES . " WHERE category_id = :id", "id={$CAT}");
                    $PostData['post_category'][] = $Read->getResult()[0]['category_parent'];
                endforeach;

                $PostData['post_category_parent'] = implode(',', array_unique($PostData['post_category_parent']));
                $PostData['post_category'] = implode(',', array_unique($PostData['post_category']));
            else:
                $PostData['post_category_parent'] = null;
                $PostData['post_category'] = null;
            endif;

            $Update->ExeUpdate(DB_POSTS, $PostData, "WHERE post_id = :id", "id={$PostId}");
            $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "O Post <b>{$PostData['post_title']}</b> Foi Atualizado Com Sucesso!"];
            $jSON['view'] = BASE . "/artigo/{$PostData['post_name']}";
            break;

        case 'sendimage':
            $NewImage = $_FILES['image'];
            $Read->FullRead("SELECT post_title, post_name FROM " . DB_POSTS . " WHERE post_id = :id", "id={$PostData['post_id']}");
            if (!$Read->getResult()):
                $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Desculpe {$_SESSION['userLogin']['user_name']}, Mas Não Foi Possível Identificar o Post Vinculado!"];
            else:
                $Upload = new Upload('../../uploads/');
                $Upload->Image($NewImage, $PostData['post_id'] . '-' . time(), IMAGE_W);
                if ($Upload->getResult()):
                    $PostData['image'] = $Upload->getResult();
                    $Create->ExeCreate(DB_POSTS_IMAGE, $PostData);
                    $jSON['tinyMCE'] = "<img title='{$Read->getResult()[0]['post_title']}' alt='{$Read->getResult()[0]['post_title']}' src='../uploads/{$PostData['image']}'/>";
                else:
                    $jSON['alert'] = ["yellow", "image", "ERRO AO ENVIAR IMAGEM", "Olá {$_SESSION['userLogin']['user_name']}, Selecione Uma Imagem JPG ou PNG Para Inserir No Post!"];
                endif;
            endif;
            break;

        case 'category_add':
            $PostData = array_map('strip_tags', $PostData);
            $CatId = $PostData['category_id'];
            unset($PostData['category_id']);

            $PostData['category_name'] = Check::Name($PostData['category_title']);
            $PostData['category_parent'] = ($PostData['category_parent'] ? $PostData['category_parent'] : null);

            $Read->FullRead("SELECT category_id FROM " . DB_CATEGORIES . " WHERE category_name = :cn AND category_id != :ci", "cn={$PostData['category_name']}&ci={$CatId}");

            if ($Read->getResult()):
                $PostData['category_name'] = $PostData['category_name'] . '-' . $CatId;
            endif;

            if ($PostData['category_parent']):
                $Read->LinkResult(DB_CATEGORIES, 'category_id', $PostData['category_parent'], 'category_tree');

                if (in_array($CatId, explode(',', $Read->getResult()[0]['category_tree']))):
                    $jSON['alert'] = ["yellow", "warning", "OPSSS", "{$_SESSION['userLogin']['user_name']}, Uma Categoria PAI Não Pode Ser Atribuída as Suas Subcategorias!"];
                    break;
                endif;

                $PostData['category_tree'] = (!empty($Read->getResult()[0]['category_tree']) ? $Read->getResult()[0]['category_tree'] . ',' . $PostData['category_parent'] : $PostData['category_parent']);
            else:
                $PostData['category_tree'] = null;
            endif;

            $Read->FullRead("SELECT category_parent FROM " . DB_CATEGORIES . " WHERE category_id = :id AND category_parent != :parent", "id={$CatId}&parent={$PostData['category_parent']}");
            if ($Read->getResult()):
                //Contriuição do André Dorneles #1856

                $PostUpdate['post_category'] = $PostData['category_parent'];
                $Update->ExeUpdate(DB_POSTS, $PostUpdate, "WHERE post_category != :catpai AND post_category_parent = :catfilha", "catpai={$PostData['category_parent']}&catfilha={$CatId}");
            endif;

            $Update->ExeUpdate(DB_CATEGORIES, $PostData, "WHERE category_id = :id", "id={$CatId}");
            $Read->FullRead("SELECT category_id, category_parent FROM " . DB_CATEGORIES . " WHERE category_parent = :parent", "parent={$CatId}");

            function loopCat() {
                global $Read, $Update;

                if ($Read->getResult()):
                    foreach ($Read->getResult() as $CAT):
                        $Read->LinkResult(DB_CATEGORIES, 'category_id', $CAT['category_parent'], 'category_tree');
                        $arrUpdate = ['category_tree' => ($Read->getResult()[0]['category_tree'] ? $Read->getResult()[0]['category_tree'] . ',' . $CAT['category_parent'] : $CAT['category_parent'])];
                        $Update->ExeUpdate(DB_CATEGORIES, $arrUpdate, "WHERE category_id = :id", "id={$CAT['category_id']}");

                        $Read->FullRead("SELECT category_id, category_parent FROM " . DB_CATEGORIES . " WHERE category_parent = :parent", "parent={$CAT['category_id']}");
                        if ($Read->getResult()):
                            loopCat();
                        endif;
                    endforeach;
                endif;
            }

            loopCat();

             $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Categoria <b>{$PostData['category_title']}</b> Foi Atualizada Com Sucesso!"];
            break;

        case 'category_remove':
            $CatId = $PostData['del_id'];
            $Read->FullRead("SELECT post_id FROM " . DB_POSTS . " WHERE post_category = :cat OR post_category_parent = :cat", "cat={$CatId}");
            if ($Read->getResult()):
                $jSON['alert'] = ["yellow", "warning", "OPSSS", "Desculpe {$_SESSION['userLogin']['user_name']}, Mas Não é Possível Remover Categorias Com Posts Associados a Ela!"];
            else:
                $Read->FullRead("SELECT category_id FROM " . DB_CATEGORIES . " WHERE category_parent = :cat", "cat={$CatId}");
                if ($Read->getResult()):
                    $jSON['alert'] = ["yellow", "warning", "OPSSS", "Desculpe {$_SESSION['userLogin']['user_name']}, Mas Não é Possível Remover Subcategorias Com Posts Associados a Ela!"];
                else:
                    $Delete->ExeDelete(DB_CATEGORIES, "WHERE category_id = :cat", "cat={$CatId}");
                    $jSON['alert'] = ["green", "checkmark", "TUDO CERTO {$_SESSION['userLogin']['user_name']}", "A Categoria Foi Excluída Com Sucesso!"];
                endif;
            endif;
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
