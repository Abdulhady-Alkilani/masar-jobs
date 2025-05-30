حسنًا، بناءً على طلبك، سأقوم بإعادة صياغة التوثيق مع التركيز على تقديم أمثلة أكثر تحديدًا لجسم الطلب (`Body`) والخرج المتوقع (`الخرج المتوقع`) لكل رابط، مستفيدًا قدر الإمكان من البيانات التي قد تنشئها ملفات الـ Seeders التي قدمتها.

**مقدمة**

هذا التوثيق يصف واجهة برمجة التطبيقات (API) لتطبيق "مسار للوظائف" (Masar Jobs) الإصدار 1. يهدف هذا التوثيق لمساعدة المطورين والمختبرين على فهم كيفية التفاعل مع الـ API باستخدام أدوات مثل Postman.

*   **عنوان URL الأساسي (Base URL):** `http://127.0.0.1:8000` (أو عنوان ومنفذ الخادم المحلي الخاص بك).
*   **بادئة المسارات:** جميع مسارات API تبدأ بـ `/api/v1/`.
*   **تنسيق البيانات:**
    *   الطلبات التي ترسل بيانات في الجسم (`Body`) **يجب** أن تستخدم تنسيق `JSON`.
    *   **يجب** إرسال ترويسة `Content-Type: application/json`.
    *   **يجب** إرسال ترويسة `Accept: application/json` لضمان الحصول على استجابة JSON.
*   **المصادقة (Authentication):**
    *   المسارات المحمية تتطلب توكن وصول (Access Token) من نوع `Bearer`.
    *   **يجب** إرسال التوكن في ترويسة `Authorization` بالشكل التالي: `Authorization: Bearer <YOUR_ACCESS_TOKEN>`.
    *   يمكن الحصول على التوكن من مسار تسجيل الدخول (`POST /api/v1/login`).

---

**1. المصادقة (Authentication)**

*(لا تتطلب مصادقة مسبقة)*

*   **1.1 تسجيل مستخدم جديد**
    *   **الرابط:** `/api/v1/register`
    *   **الوظيفة:** إنشاء حساب مستخدم جديد.
    *   **الطريقة:** `POST`
    *   **Body (Request - JSON) - مطلوب:**
        ```json
        {
            "first_name": "Sami",
            "last_name": "Ahmed",
            "username": "samiahmed",
            "email": "sami.ahmed@email.com",
            "password": "complex_password123",
            "password_confirmation": "complex_password123",
            "phone": "0551122334",
            "type": "خريج" // (أو 'خبير استشاري' أو 'مدير شركة')
        }
        ```
    *   **الخرج المتوقع (Success - 201 Created):**
        ```json
        {
            "message": "User registered successfully",
            "access_token": "3|newTokenValue...", // توكن جديد للمستخدم المسجل
            "token_type": "Bearer",
            "user": {
                "UserID": 5, // ID المستخدم الجديد
                "first_name": "Sami",
                "last_name": "Ahmed",
                "username": "samiahmed",
                "email": "sami.ahmed@email.com",
                "email_verified": 0, // أو 1 حسب الإعدادات
                "phone": "0551122334",
                "photo": null,
                "status": "مفعل",
                "type": "خريج",
                "created_at": "YYYY-MM-DDTHH:MM:SS.mmmmmmZ",
                "updated_at": "YYYY-MM-DDTHH:MM:SS.mmmmmmZ",
                "profile": {}, // تم إنشاء ملف شخصي فارغ تلقائيًا
                "skills": [],
                "company": null
            }
        }
        ```
    *   **الخرج المتوقع (Failure - 422):** إذا كانت البيانات غير صالحة (مثل `email` أو `username` مستخدم مسبقًا، كلمة المرور غير متطابقة، حقل مطلوب مفقود). ستتضمن الاستجابة كائن `errors` يوضح الحقول التي بها مشاكل.

*   **1.2 تسجيل الدخول**
    *   **الرابط:** `/api/v1/login`
    *   **الوظيفة:** مصادقة المستخدم والحصول على توكن وصول.
    *   **الطريقة:** `POST`
    *   **Body (Request - JSON) - مطلوب:** (مثال للمستخدم Admin من Seeder)
        ```json
        {
            "email": "admin@example.com",
            "password": "password"
        }
        ```
    *   **الخرج المتوقع (Success - 200 OK):** (مثال للمستخدم Admin)
        ```json
        {
            "message": "Login successful",
            "access_token": "1|IpGf7BWT...", // توكن الوصول لهذا المستخدم
            "token_type": "Bearer",
            "user": {
                "UserID": 1,
                "first_name": "Admin",
                "last_name": "User",
                "username": "admin",
                "email": "admin@example.com",
                "email_verified": 1,
                "phone": "111111111",
                "photo": null,
                "status": "مفعل",
                "type": "Admin",
                "created_at": "...",
                "updated_at": "...",
                "profile": null, // الأدمن ليس له ملف شخصي
                "skills": [],  // الأدمن ليس له مهارات (عادةً)
                "company": null // الأدمن ليس له شركة
            }
        }
        ```
    *   **الخرج المتوقع (Failure - 401/422):** بيانات اعتماد خاطئة (`auth.failed`).
    *   **الخرج المتوقع (Failure - 403):** الحساب غير مفعل (`Account is not active.`).

---

**2. الموارد العامة (Public Resources)**

*(لا تتطلب مصادقة)*

*   **2.1 عرض قائمة المقالات**
    *   **الرابط:** `/api/v1/articles`
    *   **الوظيفة:** الحصول على قائمة بالمقالات (مع تقسيم صفحات افتراضي).
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (مثال يعتمد على `ArticleSeeder`)
        ```json
        {
            "current_page": 1,
            "data": [
                {
                    "ArticleID": 2,
                    "UserID": 3,
                    "Title": "الاتجاهات الحديثة في تطوير الويب لعام 2024",
                    "Description": "استعراض لأحدث التقنيات والأطر في عالم تطوير الويب...",
                    "Date": "YYYY-MM-DD", // تاريخ النشر من Seeder
                    "Type": "استشاري",
                    "Article Photo": null,
                    "created_at": "...",
                    "updated_at": "...",
                    "user": { "UserID": 3, "first_name": "Expert", "last_name": "Consultant" }
                },
                {
                    "ArticleID": 1,
                    "UserID": 3,
                    "Title": "أهمية بناء ملف شخصي قوي للخريجين",
                    "Description": "نص المقال هنا يتحدث عن كيفية بناء ملف شخصي جذاب لأصحاب العمل...",
                    "Date": "YYYY-MM-DD",
                    "Type": "نصائح",
                    "Article Photo": null,
                     "created_at": "...",
                    "updated_at": "...",
                    "user": { "UserID": 3, "first_name": "Expert", "last_name": "Consultant" }
                }
            ],
            "first_page_url": "...",
            "from": 1,
            "last_page": 1, // أو أكثر إذا كان هناك صفحات
            "last_page_url": "...",
            "links": [ /* ... روابط التنقل بين الصفحات ... */ ],
            "next_page_url": null, // أو رابط الصفحة التالية
            "path": "http://127.0.0.1:8000/api/v1/articles",
            "per_page": 15, // عدد العناصر في الصفحة
            "prev_page_url": null,
            "to": 2, // أو عدد العناصر في هذه الصفحة
            "total": 2 // العدد الإجمالي للمقالات
        }
        ```

*   **2.2 عرض مقال محدد**
    *   **الرابط:** `/api/v1/articles/{article}` (مثال: `/api/v1/articles/1`)
    *   **الوظيفة:** الحصول على تفاصيل مقال معين بمعرفه (ID).
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (مثال للمقال ID=1 من Seeder)
        ```json
        {
            "ArticleID": 1,
            "UserID": 3,
            "Title": "أهمية بناء ملف شخصي قوي للخريجين",
            "Description": "نص المقال هنا يتحدث عن كيفية بناء ملف شخصي جذاب لأصحاب العمل...",
            "Date": "YYYY-MM-DD",
            "Type": "نصائح",
            "Article Photo": null,
            "created_at": "...",
            "updated_at": "...",
            "user": { "UserID": 3, "first_name": "Expert", "last_name": "Consultant" }
        }
        ```
    *   **الخرج المتوقع (Failure - 404 Not Found):** إذا كان معرف المقال غير موجود.

*   **2.3 عرض قائمة فرص العمل/التدريب**
    *   **الرابط:** `/api/v1/jobs`
    *   **الوظيفة:** الحصول على قائمة بفرص العمل والتدريب **المفعّلة** (مع تقسيم صفحات).
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (تنسيق مشابه لقائمة المقالات، مع بيانات الوظائف من `JobOpportunitySeeder` التي حالتها 'مفعل')

*   **2.4 عرض فرصة عمل/تدريب محددة**
    *   **الرابط:** `/api/v1/jobs/{job}` (مثال: `/api/v1/jobs/1`)
    *   **الوظيفة:** الحصول على تفاصيل فرصة عمل/تدريب معينة بمعرفها (ID).
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (تفاصيل الفرصة ID=1 من Seeder)
    *   **الخرج المتوقع (Failure - 404 Not Found):** إذا كان المعرف غير موجود.

*   **2.5 عرض قائمة الدورات التدريبية**
    *   **الرابط:** `/api/v1/courses`
    *   **الوظيفة:** الحصول على قائمة بالدورات التدريبية (مع تقسيم صفحات).
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (تنسيق مشابه لقائمة المقالات، مع بيانات الدورات من `TrainingCourseSeeder`)

*   **2.6 عرض دورة تدريبية محددة**
    *   **الرابط:** `/api/v1/courses/{course}` (مثال: `/api/v1/courses/1`)
    *   **الوظيفة:** الحصول على تفاصيل دورة تدريبية معينة بمعرفها (ID).
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (تفاصيل الدورة ID=1 من Seeder)
    *   **الخرج المتوقع (Failure - 404 Not Found):** إذا كان المعرف غير موجود.

*   **2.7 عرض قائمة الشركات**
    *   **الرابط:** `/api/v1/companies`
    *   **الوظيفة:** الحصول على قائمة بالشركات (الموافق عليها؟) (مع تقسيم صفحات).
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (قائمة الشركات من `CompanySeeder` - ملاحظة: Seeder أنشأ شركة واحدة فقط بحالة غير محددة، قد تحتاج لتحديثها أو إضافة المزيد بحالة 'approved' لترى شيئًا هنا إذا كان الكود يفلتر)

*   **2.8 عرض شركة محددة**
    *   **الرابط:** `/api/v1/companies/{company}` (مثال: `/api/v1/companies/1`)
    *   **الوظيفة:** الحصول على تفاصيل شركة معينة بمعرفها (ID).
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (تفاصيل الشركة ID=1 من Seeder)
    *   **الخرج المتوقع (Failure - 404 Not Found):** إذا كان المعرف غير موجود.

*   **2.9 عرض/بحث عن المهارات**
    *   **الرابط:** `/api/v1/skills` (يمكن إضافة بارامتر بحث مثل `?search=PHP`)
    *   **الوظيفة:** الحصول على قائمة بالمهارات المتاحة.
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (قائمة بالمهارات من `SkillSeeder`)
        ```json
        [
            { "SkillID": 1, "Name": "PHP" },
            { "SkillID": 2, "Name": "Laravel" },
            { "SkillID": 3, "Name": "JavaScript" },
            // ... باقي المهارات
        ]
        ```

---

**3. المسارات المحمية (Protected Routes)**

**(تتطلب `Bearer Token` صالح في ترويسة `Authorization`)**

*   **3.1 تسجيل الخروج**
    *   **الرابط:** `/api/v1/logout`
    *   **الوظيفة:** إلغاء صلاحية التوكن المستخدم في الطلب.
    *   **الطريقة:** `POST`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):**
        ```json
        { "message": "Successfully logged out" }
        ```
    *   **الخرج المتوقع (Failure - 401 Unauthenticated):** توكن غير صالح أو مفقود.

*   **3.2 الحصول على بيانات المستخدم الحالي**
    *   **الرابط:** `/api/v1/user`
    *   **الوظيفة:** الحصول على تفاصيل المستخدم المسجل دخوله (مع ملفه الشخصي ومهاراته).
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (مثال إذا تم تسجيل الدخول كمستخدم خريج ID=4)
        ```json
        {
            "UserID": 4,
            "first_name": "Graduate",
            "last_name": "Student",
            // ... باقي بيانات الخريج ...
            "profile": { // بيانات من ProfileSeeder
                "ProfileID": 1,
                "UserID": 4,
                "University": "King Saud University",
                "GPA": "4.5",
                "Personal Description": "Enthusiastic computer science graduate...",
                "Technical Description": "Proficient in web development...",
                "Git Hyper Link": "https://github.com/graduatestudent",
                 "created_at": "...",
                 "updated_at": "..."
            },
            "skills": [ // مهارات تم ربطها عبر UserSkillSeeder
                {
                    "SkillID": 1,
                    "Name": "PHP",
                    "pivot": { "UserID": 4, "SkillID": 1, "Stage": "متقدم" } // مثال
                },
                // ... مهارات أخرى مرتبطة
            ],
             "company": null // الخريج ليس له شركة
        }
        ```
    *   **الخرج المتوقع (Failure - 401 Unauthenticated):** توكن غير صالح أو مفقود.

*   **3.3 عرض الملف الشخصي للمستخدم الحالي**
    *   **الرابط:** `/api/v1/profile`
    *   **الوظيفة:** عرض الملف الشخصي للمستخدم الحالي.
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (مثال إذا تم تسجيل الدخول كخبير ID=3)
        ```json
        {
            "ProfileID": 2,
            "UserID": 3,
            "University": "Stanford University",
            "GPA": "3.9",
            "Personal Description": "Experienced consultant...",
            "Technical Description": "Specializing in scalable...",
            "Git Hyper Link": "https://github.com/expertconsultant",
            "created_at": "...",
            "updated_at": "..."
        }
        ```
    *   **الخرج المتوقع (Failure - 401 Unauthenticated):** توكن غير صالح أو مفقود.

*   **3.4 تحديث الملف الشخصي للمستخدم الحالي**
    *   **الرابط:** `/api/v1/profile`
    *   **الوظيفة:** تحديث بيانات الملف الشخصي للمستخدم الحالي.
    *   **الطريقة:** `PUT`
    *   **Body (Request - JSON):** أرسل الحقول المراد تحديثها فقط.
        ```json
        {
            "University": "MIT",
            "Personal Description": "Highly motivated individual seeking challenges."
        }
        ```
    *   **الخرج المتوقع (Success - 200 OK):** (بيانات الملف الشخصي الكاملة بعد التحديث)
    *   **الخرج المتوقع (Failure - 401 Unauthenticated):** توكن غير صالح أو مفقود.
    *   **الخرج المتوقع (Failure - 422 Unprocessable Entity):** بيانات غير صالحة (مثل URL غير صحيح في `Git Hyper Link`).

*   **3.5 تحديث/مزامنة مهارات المستخدم الحالي**
    *   **الرابط:** `/api/v1/profile/skills`
    *   **الوظيفة:** تحديد قائمة المهارات الكاملة للمستخدم.
    *   **الطريقة:** `POST`
    *   **Body (Request - JSON) - مطلوب:**
        *   **مثال 1 (IDs فقط):** ربط المهارات 2 (Laravel) و 13 (Git) وحذف الباقي.
            ```json
            { "skills": [2, 13] }
            ```
        *   **مثال 2 (IDs مع المستوى):** ربط المهارة 2 (Laravel) بمستوى 'متقدم' والمهارة 3 (JavaScript) بمستوى 'متوسط'.
            ```json
            {
                "skills": {
                    "2": { "Stage": "متقدم" },
                    "3": { "Stage": "متوسط" }
                }
            }
            ```
    *   **الخرج المتوقع (Success - 200 OK):** (بيانات المستخدم مع قائمة `skills` المحدثة)
    *   **الخرج المتوقع (Failure - 401 Unauthenticated):** توكن غير صالح أو مفقود.
    *   **الخرج المتوقع (Failure - 422 Unprocessable Entity):** حقل `skills` مفقود، أو المصفوفة فارغة، أو معرفات المهارات غير موجودة، أو قيمة `Stage` غير صالحة.

*   **3.6 عرض طلبات التوظيف الخاصة بالمستخدم**
    *   **الرابط:** `/api/v1/my-applications`
    *   **الوظيفة:** عرض قائمة بطلبات التوظيف التي قدمها المستخدم الحالي.
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (مثال إذا تم تسجيل الدخول كخريج ID=4 والـ Seeders تعمل)
        ```json
        [
            {
                "ID": 1, // ID الطلب
                "UserID": 4,
                "JobID": 1, // ID الوظيفة
                "Status": "Reviewed", // مثال لحالة من Seeder
                "Date": "YYYY-MM-DD", // تاريخ التقديم
                "Description": "Applied via seeder.",
                "CV": "/path/to/default_cv.pdf",
                "created_at": "...",
                "updated_at": "...",
                "job_opportunity": { // تفاصيل الوظيفة المرتبطة
                    "JobID": 1,
                    "Job Title": "Junior Web Developer",
                    "Type": "وظيفة"
                }
            },
            // ... قد يكون هناك طلبات أخرى للخريج
        ]
        ```
    *   **الخرج المتوقع (Failure - 401 Unauthenticated):** توكن غير صالح أو مفقود.

*   **3.7 تقديم طلب لوظيفة**
    *   **الرابط:** `/api/v1/jobs/{job_opportunity}/apply` (مثال: `/api/v1/jobs/2/apply`)
    *   **الوظيفة:** تقديم طلب لفرصة عمل أو تدريب محددة.
    *   **الطريقة:** `POST`
    *   **Body (Request - JSON):** (اختياري)
        ```json
        {
            "Description": "My cover letter notes.",
            "CV": "/path/cv.pdf"
        }
        ```
    *   **الخرج المتوقع (Success - 201 Created):** (بيانات طلب التوظيف الجديد)
        ```json
        {
            "ID": 3, // ID الطلب الجديد
            "UserID": 4, // ID المستخدم الحالي
            "JobID": 2, // ID الوظيفة
            "Status": "Pending",
            "Date": "YYYY-MM-DD", // تاريخ الآن
            "Description": "My cover letter notes.",
            "CV": "/path/cv.pdf",
             "created_at": "...",
            "updated_at": "..."
        }
        ```
    *   **الخرج المتوقع (Failure - 401/404/409/400):** كما ذكر سابقاً.

*   **3.8 إلغاء طلب توظيف**
    *   **الرابط:** `/api/v1/my-applications/{job_application}` (مثال: `/api/v1/my-applications/1`)
    *   **الوظيفة:** إلغاء طلب توظيف قدمه المستخدم الحالي.
    *   **الطريقة:** `DELETE`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 204 No Content):** لا يوجد محتوى.
    *   **الخرج المتوقع (Failure - 401/403/404):** كما ذكر سابقاً.

*   **3.9 عرض تسجيلات الدورات الخاصة بالمستخدم**
    *   **الرابط:** `/api/v1/my-enrollments`
    *   **الوظيفة:** عرض قائمة بالدورات التي سجل فيها المستخدم الحالي.
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (مثال إذا تم تسجيل الدخول كخريج ID=4 والـ Seeders تعمل) مصفوفة بتسجيلات الدورات.
    *   **الخرج المتوقع (Failure - 401):** غير مصادق عليه.

*   **3.10 التسجيل في دورة تدريبية**
    *   **الرابط:** `/api/v1/courses/{training_course}/enroll` (مثال: `/api/v1/courses/1/enroll`)
    *   **الوظيفة:** تسجيل المستخدم الحالي في دورة تدريبية محددة.
    *   **الطريقة:** `POST`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 201 Created):** (بيانات التسجيل الجديد)
    *   **الخرج المتوقع (Failure - 401/404/409):** كما ذكر سابقاً.

*   **3.11 إلغاء التسجيل في دورة**
    *   **الرابط:** `/api/v1/my-enrollments/{enrollment}` (مثال: `/api/v1/my-enrollments/1`)
    *   **الوظيفة:** إلغاء تسجيل المستخدم الحالي من دورة.
    *   **الطريقة:** `DELETE`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 204 No Content):** لا يوجد محتوى.
    *   **الخرج المتوقع (Failure - 401/403/404):** كما ذكر سابقاً.

*   **3.12 الحصول على التوصيات**
    *   **الرابط:** `/api/v1/recommendations`
    *   **الوظيفة:** الحصول على قائمة بالوظائف والدورات الموصى بها للمستخدم الحالي.
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):**
        ```json
        {
            "recommended_jobs": [ /* مصفوفة بفرص العمل الموصى بها (قد تكون فارغة) */ ],
            "recommended_courses": [ /* مصفوفة بالدورات الموصى بها (قد تكون فارغة) */ ]
        }
        ```
    *   **الخرج المتوقع (Failure - 401):** غير مصادق عليه.

*   **3.13 عرض المجموعات (Groups)**
    *   **الرابط:** `/api/v1/groups`
    *   **الوظيفة:** الحصول على قائمة بالمجموعات (مثل روابط تيليجرام).
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (قائمة من `GroupSeeder`)
    *   **الخرج المتوقع (Failure - 401):** غير مصادق عليه.

*   **3.14 عرض مجموعة محددة**
    *   **الرابط:** `/api/v1/groups/{group}` (مثال: `/api/v1/groups/1`)
    *   **الوظيفة:** عرض تفاصيل مجموعة معينة.
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (تفاصيل المجموعة ID=1)
    *   **الخرج المتوقع (Failure - 401):** غير مصادق عليه.
    *   **الخرج المتوقع (Failure - 404):** المعرف غير موجود.

---

**4. مسارات مدير الشركة (Company Manager Routes)**

**(تتطلب `Bearer Token` لمستخدم نوعه `'مدير شركة'`)**

*   **4.1 عرض تفاصيل شركة المدير الحالية**
    *   **الرابط:** `/api/v1/company-manager/company`
    *   **الوظيفة:** الحصول على تفاصيل الشركة المرتبطة بالمدير.
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (بيانات الشركة ID=1 من `CompanySeeder`)
    *   **الخرج المتوقع (Failure - 401/403/404):** كما ذكر سابقاً.

*   **4.2 تحديث تفاصيل شركة المدير الحالية**
    *   **الرابط:** `/api/v1/company-manager/company`
    *   **الوظيفة:** تحديث بيانات شركة المدير الحالي.
    *   **الطريقة:** `PUT`
    *   **Body (Request - JSON):** حقول الشركة المراد تحديثها.
        ```json
        {
            "Phone": "0119998877",
            "Description": "Leading IT solutions provider in KSA."
        }
        ```
    *   **الخرج المتوقع (Success - 200 OK):** (بيانات الشركة المحدثة)
    *   **الخرج المتوقع (Failure - 401/403/404/422):** كما ذكر سابقاً.

*   **4.3 إدارة فرص العمل (CRUD)**
    *   `GET /api/v1/company-manager/jobs`: عرض قائمة بفرص العمل المنشورة بواسطة **هذا المدير فقط**. (مثال: يعيد الفرص ID=1, ID=2 إذا تم تسجيل الدخول كمدير ID=2)
    *   `POST /api/v1/company-manager/jobs`: إنشاء فرصة عمل جديدة.
        *   **Body (Request - JSON):** بيانات الفرصة الجديدة (بدون UserID).
            ```json
            {
                "Job Title": "Frontend Developer",
                "Job Description": "React expert needed.",
                "Qualification": "Bachelor's degree, 2+ years experience.",
                "Site": "Jeddah",
                "Date": "YYYY-MM-DD", // تاريخ النشر
                "Skills": "React, JavaScript, HTML, CSS, Git",
                "Type": "وظيفة",
                "End Date": "YYYY-MM-DD", // تاريخ انتهاء التقديم
                "Status": "مفعل"
            }
            ```
        *   **الخرج (Success - 201 Created):** بيانات الفرصة الجديدة مع UserID الخاص بالمدير.
    *   `GET /api/v1/company-manager/jobs/{job}`: عرض تفاصيل فرصة عمل نشرها هذا المدير. (فشل 403/404 إذا كانت لمدير آخر)
    *   `PUT /api/v1/company-manager/jobs/{job}`: تحديث فرصة عمل نشرها هذا المدير. (فشل 403/404 إذا كانت لمدير آخر)
    *   `DELETE /api/v1/company-manager/jobs/{job}`: حذف فرصة عمل نشرها هذا المدير. (فشل 403/404 إذا كانت لمدير آخر)
        *   **الخرج (Success - 204 No Content)**

*   **4.4 إدارة الدورات التدريبية (CRUD) (إذا مسموح)**
    *   نفس منطق إدارة فرص العمل، ولكن للدورات التي ينشرها المدير.

*   **4.5 عرض المتقدمين لوظيفة**
    *   **الرابط:** `/api/v1/company-manager/jobs/{job_opportunity}/applicants` (مثال: `/api/v1/company-manager/jobs/1/applicants`)
    *   **الوظيفة:** عرض قائمة بالمتقدمين لوظيفة نشرها المدير.
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (مثال لوظيفة ID=1)
        ```json
        [
            {
                "ID": 1, // ID الطلب
                "UserID": 4, // ID المتقدم (الخريج)
                "JobID": 1,
                "Status": "Reviewed",
                // ... باقي بيانات الطلب ...
                "user": { // بيانات المتقدم
                    "UserID": 4,
                    "first_name": "Graduate",
                    "last_name": "Student",
                    "email": "graduate@student.com",
                    "phone": "444444444",
                    "profile": { // ملف المتقدم الشخصي
                       "ProfileID": 1,
                       "University": "King Saud University",
                        // ...
                    }
                }
            },
            // ... قد يكون هناك متقدمين آخرين للوظيفة ID=1
        ]
        ```
    *   **الخرج المتوقع (Failure - 401/403/404):** كما ذكر سابقاً.

*   **4.6 عرض المسجلين بدورة**
    *   **الرابط:** `/api/v1/company-manager/courses/{training_course}/enrollees` (مثال: `/api/v1/company-manager/courses/1/enrollees`)
    *   **الوظيفة:** عرض قائمة بالمسجلين في دورة نشرها المدير.
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (تنسيق مشابه للمتقدمين، ولكن ببيانات التسجيل والمستخدمين المسجلين في الدورة ID=1)
    *   **الخرج المتوقع (Failure - 401/403/404):** كما ذكر سابقاً.

---

**5. مسارات الاستشاري (Consultant Routes)**

**(تتطلب `Bearer Token` لمستخدم نوعه `'خبير استشاري'`)**

*   **5.1 إدارة المقالات (CRUD)**
    *   `GET /api/v1/consultant/articles`: عرض قائمة بالمقالات المنشورة بواسطة **هذا الاستشاري فقط**. (مثال: يعيد المقالات ID=1, ID=2 إذا تم تسجيل الدخول كاستشاري ID=3)
    *   `POST /api/v1/consultant/articles`: إنشاء مقال جديد.
        *   **Body (Request - JSON):** بيانات المقال الجديد (بدون UserID).
            ```json
            {
                "Title": "نصائح لمقابلات العمل الفنية",
                "Description": "كيف تستعد وتتألق في المقابلات التقنية...",
                "Date": "YYYY-MM-DD", // تاريخ النشر
                "Type": "نصائح",
                "Article Photo": null
            }
            ```
        *   **الخرج (Success - 201 Created):** بيانات المقال الجديد مع UserID الخاص بالاستشاري.
    *   `GET /api/v1/consultant/articles/{article}`: عرض تفاصيل مقال نشره هذا الاستشاري. (فشل 403/404 إذا كان لاستشاري آخر)
    *   `PUT /api/v1/consultant/articles/{article}`: تحديث مقال نشره هذا الاستشاري. (فشل 403/404 إذا كان لاستشاري آخر)
    *   `DELETE /api/v1/consultant/articles/{article}`: حذف مقال نشره هذا الاستشاري. (فشل 403/404 إذا كان لاستشاري آخر)
        *   **الخرج (Success - 204 No Content)**

---

**6. مسارات الأدمن (Admin Routes)**

**(تتطلب `Bearer Token` لمستخدم نوعه `'Admin'`)**

*   **6.1 إدارة المستخدمين (CRUD)**
    *   `GET /api/v1/admin/users`: عرض **جميع** المستخدمين مع تقسيم صفحات.
    *   `POST /api/v1/admin/users`: إنشاء مستخدم جديد بأي نوع وحالة.
        *   **Body (Request - JSON):** بيانات المستخدم كاملة.
            ```json
            {
                "first_name": "Admin",
                "last_name": "Test",
                "username": "admintest",
                "email": "admintest@example.com",
                "password": "password123", // لا يحتاج تأكيد هنا
                "phone": "0598765432",
                "type": "Admin",
                "status": "مفعل",
                "email_verified": true
            }
            ```
        *   **الخرج (Success - 201 Created):** بيانات المستخدم الجديد.
    *   `GET /api/v1/admin/users/{user}`: عرض أي مستخدم بمعرفه.
    *   `PUT /api/v1/admin/users/{user}`: تحديث أي مستخدم (بما في ذلك النوع والحالة).
        *   **Body (Request - JSON):** الحقول المراد تحديثها (كلمة المرور اختيارية).
            ```json
             { "status": "معلق", "type": "خريج" }
            ```
    *   `DELETE /api/v1/admin/users/{user}`: حذف أي مستخدم (باستثناء المستخدم الحالي إذا كان هو الأدمن).
        *   **الخرج (Success - 204 No Content)**

*   **6.2 إدارة المهارات (CRUD)**
    *   `GET /api/v1/admin/skills`: عرض **جميع** المهارات.
    *   `POST /api/v1/admin/skills`: إضافة مهارة جديدة.
        *   **Body (Request - JSON):**
            ```json
            { "Name": "Docker" }
            ```
        *   **الخرج (Success - 201 Created):** بيانات المهارة الجديدة.
    *   `GET /api/v1/admin/skills/{skill}`: عرض أي مهارة.
    *   `PUT /api/v1/admin/skills/{skill}`: تحديث أي مهارة.
    *   `DELETE /api/v1/admin/skills/{skill}`: حذف أي مهارة.
        *   **الخرج (Success - 204 No Content)**

*   **6.3 إدارة المجموعات (CRUD)**
    *   `GET /api/v1/admin/groups`: عرض **جميع** المجموعات.
    *   `POST /api/v1/admin/groups`: إضافة مجموعة جديدة.
        *   **Body (Request - JSON):**
            ```json
            { "Telegram Hyper Link": "https://t.me/new_group_link" }
            ```
        *   **الخرج (Success - 201 Created):** بيانات المجموعة الجديدة.
    *   `GET /api/v1/admin/groups/{group}`: عرض أي مجموعة.
    *   `PUT /api/v1/admin/groups/{group}`: تحديث أي مجموعة.
    *   `DELETE /api/v1/admin/groups/{group}`: حذف أي مجموعة.
        *   **الخرج (Success - 204 No Content)**

*   **6.4 إدارة الشركات (CRUD)**
    *   `GET /api/v1/admin/companies`: عرض **جميع** الشركات (بكل الحالات: pending, approved, rejected).
    *   `POST /api/v1/admin/companies`: إنشاء شركة جديدة مباشرة (مع تحديد UserID وحالة approved مثلاً).
        *   **Body (Request - JSON):** بيانات الشركة كاملة + UserID.
    *   `GET /api/v1/admin/companies/{company}`: عرض أي شركة.
    *   `PUT /api/v1/admin/companies/{company}`: تحديث أي شركة (بما في ذلك حقل `status`).
    *   `DELETE /api/v1/admin/companies/{company}`: حذف أي شركة.

*   **6.5 إدارة المقالات (CRUD)**
    *   `GET /api/v1/admin/articles`: عرض **جميع** المقالات.
    *   `POST /api/v1/admin/articles`: إنشاء مقال جديد باسم أي مستخدم (تحديد UserID في Body).
    *   `GET /api/v1/admin/articles/{article}`: عرض أي مقال.
    *   `PUT /api/v1/admin/articles/{article}`: تحديث أي مقال.
    *   `DELETE /api/v1/admin/articles/{article}`: حذف أي مقال.

*   **6.6 إدارة فرص العمل (CRUD)**
    *   `GET /api/v1/admin/jobs`: عرض **جميع** فرص العمل.
    *   `POST /api/v1/admin/jobs`: إنشاء فرصة عمل جديدة باسم أي شركة/مدير (تحديد UserID في Body).
    *   `GET /api/v1/admin/jobs/{job}`: عرض أي فرصة عمل.
    *   `PUT /api/v1/admin/jobs/{job}`: تحديث أي فرصة عمل (بما في ذلك `Status`).
    *   `DELETE /api/v1/admin/jobs/{job}`: حذف أي فرصة عمل.

*   **6.7 إدارة الدورات التدريبية (CRUD)**
    *   `GET /api/v1/admin/courses`: عرض **جميع** الدورات.
    *   `POST /api/v1/admin/courses`: إنشاء دورة جديدة باسم أي مستخدم (تحديد UserID في Body).
    *   `GET /api/v1/admin/courses/{course}`: عرض أي دورة.
    *   `PUT /api/v1/admin/courses/{course}`: تحديث أي دورة.
    *   `DELETE /api/v1/admin/courses/{course}`: حذف أي دورة.

*   **6.8 عرض طلبات إنشاء الشركات المعلقة**
    *   **الرابط:** `/api/v1/admin/company-requests`
    *   **الوظيفة:** الحصول على قائمة بالشركات التي تنتظر الموافقة (status='pending').
    *   **الطريقة:** `GET`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** مصفوفة بالشركات التي حالتها 'pending' (قد تكون فارغة).
    *   **الخرج المتوقع (Failure - 401/403):** غير مصادق عليه أو ليس أدمن.

*   **6.9 الموافقة على طلب شركة**
    *   **الرابط:** `/api/v1/admin/company-requests/{company}/approve` (مثال: `/api/v1/admin/company-requests/2/approve`)
    *   **الوظيفة:** تغيير حالة الشركة إلى 'approved'.
    *   **الطريقة:** `PUT`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):**
        ```json
        {
            "message": "Company approved successfully.",
            "company": { // بيانات الشركة المحدثة مع status='approved'
                 "CompanyID": 2, "UserID": 5, // مثال
                 "Name": "New Pending Company",
                 // ...
                 "status": "approved",
                 // ...
             }
        }
        ```
    *   **الخرج المتوقع (Failure - 401/403/404/400):** كما ذكر سابقاً.

*   **6.10 رفض طلب شركة**
    *   **الرابط:** `/api/v1/admin/company-requests/{company}/reject` (مثال: `/api/v1/admin/company-requests/2/reject`)
    *   **الوظيفة:** تغيير حالة الشركة إلى 'rejected'.
    *   **الطريقة:** `PUT`
    *   **Body:** لا يوجد.
    *   **الخرج المتوقع (Success - 200 OK):** (رسالة نجاح وبيانات الشركة المحدثة مع status='rejected')
    *   **الخرج المتوقع (Failure - 401/403/404/400):** كما ذكر سابقاً.

---

هذا التوثيق الآن أكثر تفصيلاً بخصوص أمثلة الدخل والخرج المتوقعة، مما يجعله أفضل للاختبار باستخدام Postman. تذكر أن الخرج الفعلي قد يختلف قليلاً بناءً على التنفيذ الدقيق للمتحكمات واستخدام API Resources.