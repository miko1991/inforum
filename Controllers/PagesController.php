<?php


namespace Controllers;


use Kernel\Authentication;
use Kernel\Kernel;
use Kernel\PageSection;
use Kernel\Template;

class PagesController
{
    public function index() {
        $stmt = Kernel::$db->prepare("SELECT * FROM pages");
        $stmt->execute();
        $pages = $stmt->fetchAll(\PDO::FETCH_OBJ);
        Template::view("AdminPageBrowse.html", "./Views/", [
            "pages" => $pages
        ]);
    }

    public function create() {
        Template::view("AdminPageAdd.html", "./Views/");
    }

    public function createProcess() {
        $stmt = Kernel::$db->prepare("INSERT INTO pages (title, content, slug) VALUES (?, ?, ?)");
        $stmt->execute([$_POST["title"], $_POST["blocks"], $_POST["slug"]]);
    }

    public function edit($id) {
        Template::view("AdminPageEdit.html", "./Views/", [
            "id" => $id
        ]);
    }

    public function editProcess() {
        $stmt = Kernel::$db->prepare("UPDATE pages SET title = ?, slug = ?, content = ? WHERE id = ?");
        $stmt->execute([$_POST["title"], $_POST["slug"], $_POST["blocks"], $_POST["id"]]);
    }

    public function fetchPage($id) {
        $stmt = Kernel::$db->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $page = $stmt->fetch(\PDO::FETCH_OBJ);

        echo json_encode($page);
    }

    public function getPage($slug) {
        $user = Authentication::user();
        $stmt = Kernel::$db->prepare("SELECT * FROM pages WHERE slug = ?");
        $stmt->execute([$slug]);
        $page = $stmt->fetch(\PDO::FETCH_OBJ);

        $contents = json_decode($page->content, true);
        foreach ($contents as $block => &$array) {
            foreach ($array["columns"] as &$column) {
                $arr = PageSection::parse($column);
                $column["html"] = $arr["html"];
                $column["data"] = $arr["data"];
            }
        }
        Template::setPageContent($contents);



        Template::view("Page.html", "./Views/", [
            "page" => $page,
            "user" => $user
        ]);
    }
}