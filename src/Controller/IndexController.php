<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    public function install()
    : Response
    {
        //@Get("/api/install")
        
        $entityManager = $this->getDoctrine()->getManager();
        
        $maxcount    = 1000;
        $firstnames  = [ "Александр", "Иван", "Максим", "Олег", "Марат", "Людмила", "Оксана" ];
        $secondnames = [ "Ткаченко", "Жуков", "Чкалов", "Путин", "Шамузинов", "Дахно", "Карась" ];
        $surnames    = [ "Александрович", "Иванович", "Максимович", "Олегович", "Маратович", "Евгеньевич", "Джонович" ];
    
        
        $usersRepository = new UsersRepository($this->getDoctrine());
        $count = $usersRepository->count([]);
    
        $ID = false;
        if ($count < $maxcount)
        {
            for ($next_item = $count; $next_item < $maxcount; $next_item++)
            {
                //заполним таблицу
                $rnd_f  = rand(0, 6);
                $rnd_s  = rand(0, 6);
                $rnd_ss = rand(0, 6);
                
                $Users = new Users();
                $Users->setFirstname($firstnames[$rnd_f]);
                $Users->setSecondname($secondnames[$rnd_s]);
                $Users->setSurname($surnames[$rnd_ss]);
    
                $entityManager->persist($Users);
                $entityManager->flush();
                $ID = $Users->getId();
            }
        }
        
        $result = [ 'result' => $ID ];
        return new Response(json_encode($result));
    }
    
    
    public function getid($id)
    : Response
    {
        //@Get("/api/get/{id}")
        $result = new Response();
        $id     = $id ?? 0;
        if ($id > 0)
        {
            $usersRepository = new UsersRepository($this->getDoctrine());
            $User = $usersRepository->find($id);
    
            $UserArray = [
                "id" =>  $User->getId(),
                "firstname" =>  $User->getFirstname(),
                "secondname" =>  $User->getSecondname(),
                "surname" =>  $User->getSurname(),
            ];
            $result->setContent(json_encode($UserArray, JSON_UNESCAPED_UNICODE));
        }
        else
        {
            $result->setStatusCode(404);
        }

        return $result;
    }

    public function list($id, $limit)
    : Response
    {
        // @Get("/api/list/{id}/{limit}")
        $result = new Response();
        $id     = $id ?? 0;
        $limit  = $limit ?? 1;
        if ($id > 0)
        {
            $usersRepository = new UsersRepository($this->getDoctrine());
            $Users = $usersRepository->findById($id, $limit);
            
            $result->setContent(json_encode($Users, JSON_UNESCAPED_UNICODE));
        }
        else
        {
            $result->setStatusCode(404);
        }

        return $result;
    }

//    public function search($find, $id, $limit)
//    : Response
//    {
//        //@Get("/api/search/{find}/{id}/{limit}")
//        $Users  = new Users();
//        $result = $Users->FullText($find, $id, $limit);
//
//        return $result;
//    }
//
//    private function saveUsers($json)
//    : Response
//    {
//        $result = false;
//        if (isset($json["firstname"]) && isset($json["secondname"]) && isset($json["surname"]))
//        {
//            $User             = new Users();
//            $User->firstname  = $json["firstname"];
//            $User->secondname = $json["secondname"];
//            $User->surname    = $json["surname"];
//
//            if (isset($json["id"]))
//            {
//                $User->id = $json["id"];
//                $result   = $User->update();
//            }
//            else
//            {
//                $result = $User->save();
//            }
//
//            if ($result)
//            {
//                $result = $User;
//            }
//        }
//
//        return $result;
//    }
//
//    public function post()
//    : Response
//    {
//        //@Post("/api/post")
//        $result = [];
//
//        $json_items = $this->request->getJsonRawBody(true);
//        foreach ($json_items as $json)
//        {
//            unset($json["id"]);
//            $UserInfo = $this->saveUsers($json);
//            if ($UserInfo)
//            {
//                $this->setStatusCode(201);
//                $result[] = $UserInfo;
//            }
//            else
//            {
//                $this->setStatusCode(404);
//            }
//        }
//
//        return $result;
//    }
//
//    public function put()
//    : Response
//    {
//        //@Put("/api/put")
//        $result     = [];
//        $json_items = $this->request->getJsonRawBody(true);
//        foreach ($json_items as $json)
//        {
//            $id = $json["id"] ?? 0;
//            if ($id > 0)
//            {
//                $UserInfo = $this->saveUsers($json);
//                if ($UserInfo)
//                {
//                    $this->setStatusCode(201);
//                }
//                else
//                {
//                    $this->setStatusCode(404);
//                }
//            }
//        }
//
//        return $result;
//    }
//
//    public function delete()
//    : Response
//    {
//        //@Delete("/api/delete")
//        $result     = [];
//        $json_items = $this->request->getJsonRawBody(true);
//        foreach ($json_items as $json)
//        {
//            $id = $json["id"] ?? 0;
//            if ($id > 0)
//            {
//                $Users     = new Users();
//                $Users->id = $id;
//                $Users->delete();
//
//                $this->setStatusCode(201);
//            }
//            else
//            {
//                $this->setStatusCode(404);
//            }
//        }
//
//        return $result;
//    }

}

