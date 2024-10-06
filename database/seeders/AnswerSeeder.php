// In your database/seeds directory, create a seeder file:
// database/seeds/AnswerSeeder.php
<?php
use Illuminate\Database\Seeder;
use App\Models\Answer;

class AnswerSeeder extends Seeder
{
    public function run()
    {
        Answer::create([
            'question_id' => 1,
            'auth_user_id' => 1,
            'challenge_user_id' => 2,
            'auth_user_answer' => 'A',
            'challenge_user_answer' => 'B',
            'original_answer' => 'A'
        ]);
    }
}

