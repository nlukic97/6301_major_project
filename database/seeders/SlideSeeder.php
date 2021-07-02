<?php

namespace Database\Seeders;

use App\Models\Slide;
use Illuminate\Database\Seeder;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Slide::factory()->create([
            'owner_id'=>1,
            'title'=>'Slide template',
            'data'=>'[{"type":"slide","content":"## Help\n- click \'New Slide\' to create a slide like this\n- click \'exercise slide\' to create a coding slide (such as the next one)"},{"type":"exercise","content":"Exercise","data":{"xml":"<!DOCTYPE html>\n<html>\n<head>\n  <title>JS Bin</title>\n\n  <style>\n  </style>\n</head>\n<body>\n    <!-- type some HTML... -->\n\n</body>\n</html>","javaScript":"//type in some javaScript [o_0] ...\ndocument.write(\'Hello\')\n/** Click on \'Execute\' */"}},{"type":"exercise","content":"Exercise","data":{"xml":"<!DOCTYPE html>\n<html>\n<head>\n  <title>JS Bin</title>\n\n  <style>\n  </style>\n</head>\n<body>\n  <h1 id=\'heading\'>You can act as you \n    would in any other text editor.\n  </h1>\n  <h2 id=\'heading2\'>\n    Click \'Execute\' to run the JavaScript.\n  </h2>\n\n</body>\n</html>","javaScript":"//type in some javaScript [o_0] ...\ndocument.getElementById(\'heading\')\n.innerText = \'Hello world !\';\n\n\n//type in some javaScript [o_0] ...\ndocument.getElementById(\'heading2\')\n.innerText = \'Congratulations, you are getting the hang of it.\';"}},{"type":"slide","content":"<i>Example slide</i>\n\n## h2\n### h3\n#### h4\n##### h5\n###### h6\n1. List item\n2. list item \n\n- list item\n- list item"}]',

        ]);

        Slide::factory()->create([
            'owner_id'=>1,
            'title'=>'Slide template 2',
            'data'=>'[{"type":"exercise","content":"Exercise","data":{"xml":"<!DOCTYPE html>\n<html>\n<head>\n  <title>JS Bin</title>\n   \n  <style>\n    body {\n      margin:0;\n      padding:0;\n      box-sizing:border-box;\n    }\n  </style>\n</head>\n<body>\n    <!-- type some HTML... -->\n  <h1 id=\'howdy\'>Howdy</h1>\n\n</body>\n</html>","javaScript":"(function(){\n\tdocument.write(\'e\')\n  console.log(\'e\')\n})()"}},{"type":"slide","content":"# Good job !\n"},{"type":"exercise","content":"Exercise","data":{"xml":"<!DOCTYPE html>\n<html>\n<head>\n  <title>JS Bin</title>\n\n  <style>\n  </style>\n</head>\n<body>\n    <!-- type some HTML... -->\n\n</body>\n</html>","javaScript":"//type in some javaScript [o_0] ..."}}]',

        ]);

    }
}
