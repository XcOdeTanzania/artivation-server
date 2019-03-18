<?php
use App\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new category();
        $category->name = 'animals';
        $category->save();

        $category = new category();
        $category->name = 'beaches';
        $category->save();

        $category = new category();
        $category->name = 'cartoons';
        $category->save();

        $category = new category();
        $category->name = 'flowers';
        $category->save();

        
        $category = new category();
        $category->name = 'grafitti';
        $category->save();

        $category = new category();
        $category->name = 'house';
        $category->save();

        $category = new category();
        $category->name = 'insects';
        $category->save();

        $category = new category();
        $category->name = 'love';
        $category->save();

        $category = new category();
        $category->name = 'nature';
        $category->save();

        $category = new category();
        $category->name = 'people';
        $category->save();

        $category = new category();
        $category->name = 'tree';
        $category->save();
    }
}
