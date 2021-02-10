<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

//use http\Client\Request;

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
        $count           = $usersRepository->count([]);
        
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
        
        $res = [ 'result' => $ID ];
        
        $result = $this->json($res);
        
        return $result;
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
            $UserArray       = [];
            $UserArray[]     = $usersRepository->find($id)->toArray();
            
            $result = $this->json($UserArray);
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
            $Users           = $usersRepository->findById($id, $limit);
            $result          = $this->json($Users);
        }
        else
        {
            $result->setStatusCode(404);
        }
        
        return $result;
    }
    
    public function search($find, $id, $limit)
    : Response
    {
        //@Get("/api/search/{find}/{id}/{limit}")
        $usersRepository = new UsersRepository($this->getDoctrine());
        $findresult      = $usersRepository->findByText($find, $id, $limit);
        $result          = $this->json($findresult);
        
        return $result;
    }
    
    private function saveUsers($json)
    {
        $result = false;
        if (isset($json["firstname"]) && isset($json["secondname"]) && isset($json["surname"]))
        {
            $entityManager = $this->getDoctrine()->getManager();
            if (isset($json["id"]))
            {
                $usersRepository = new UsersRepository($this->getDoctrine());
                $User            = $usersRepository->find((int)$json["id"]);
            }
            else
            {
                $User = new Users();
            }
            $User->setFirstname($json["firstname"]);
            $User->setSecondname($json["secondname"]);
            $User->setSurname($json["surname"]);
            
            $entityManager->persist($User);
            $entityManager->flush();
            $UserId = $User->getId();
            
            if ($UserId)
            {
                $result = $User;
            }
        }
        
        return $result;
    }
    
    public function post()
    : Response
    {
        //@Post("/api/post")
        $result = new Response();
    
        $content    = (new Request())->getContent();
        $json_items = json_decode($content, true);
        foreach ($json_items as $json)
        {
            unset($json["id"]);
            $UserInfo = $this->saveUsers($json);
            if ($UserInfo)
            {
                $UserInfoArray   = [];
                $UserInfoArray[] = json_decode($this->json($UserInfo)->getContent(), true);
                $result          = $this->json($UserInfoArray)->setStatusCode(201);
            }
            else
            {
                $result->setStatusCode(404);
            }
        }
        
        return $result;
    }
    
    public function put()
    : Response
    {
        //@Put("/api/put")
        $result = new Response();
        
        $content    = (new Request())->getContent();
        $json_items = json_decode($content, true);
        foreach ($json_items as $json)
        {
            $id = $json["id"] ?? 0;
            if ($id > 0)
            {
                $UserInfo = $this->saveUsers($json);
                if ($UserInfo)
                {
                    $result->setStatusCode(201);
                }
                else
                {
                    $result->setStatusCode(404);
                }
            }
        }
        
        return $result;
    }
    
    public function delete()
    : Response
    {
        //@Delete("/api/delete")
        $result = new Response();
    
        $content    = (new Request())->getContent();
        $json_items = json_decode($content, true);
        foreach ($json_items as $json)
        {
            $id = $json["id"] ?? 0;
            if ($id > 0)
            {
                $entityManager = new UsersRepository($this->getDoctrine());
                $User          = $entityManager->find($id);
                
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($User);
                $entityManager->remove($User);
                $entityManager->flush();
                
                $result->setStatusCode(201);
            }
            else
            {
                $result->setStatusCode(404);
            }
        }
        
        return $result;
    }
}

