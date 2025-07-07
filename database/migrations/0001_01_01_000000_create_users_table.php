    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('users', function (Blueprint $table) {
                // $table->id(); // Default Laravel convention
                $table->bigIncrements('UserID'); // Matching your schema 'UserID'
                $table->string('first_name');
                $table->string('last_name');
                $table->string('username')->unique();
                $table->string('email')->unique();
                $table->boolean('email_verified')->default(false); // Using boolean as per schema desc
                // $table->timestamp('email_verified_at')->nullable(); // Alternative Laravel way
                $table->string('password');
                $table->string('phone')->nullable(); // Optional phone
                $table->string('photo')->nullable(); // Optional photo path/URL
                $table->string('status')->default('مفعل'); // Status with default
                $table->string('type'); // User type (خريج, خبير استشاري, مدير شركة, Admin)
                // $table->date('sign_up_date'); // Your schema has this - covered by created_at
                $table->rememberToken();
                $table->timestamps(); // Adds created_at (for sign_up_date) and updated_at
            });

            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });

            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('users');
            Schema::dropIfExists('password_reset_tokens');
            Schema::dropIfExists('sessions');
        }
    };
