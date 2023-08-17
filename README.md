# LeanPHP
LeanPHP is for Lean and Better PHP Development.

LeanPress/
│
├── config/             # Konfigürasyon dosyaları
│   ├── database.php
│   ├── app.php
│   └── ...
│
├── src/                 # Ana kaynak kodları
│   ├── Core/
│   │   ├── Database/    # ORM ve veritabanı işlevleri
│   │   ├── Http/        # Request, Response ve diğer HTTP işlevleri
│   │   ├── Middleware/
│   │   └── Router/
│   │
│   ├── Model/
│   │   ├── User.php
│   │   └── Post.php
│   │
│   ├── Controller/
│   │   ├── ApiController/    # API için kontrollerler
│   │   └── WebController/    # Web arayüzü için kontrollerler
│   │
│   ├── Helpers/
│   │   ├── JWTHelper.php
│   │   └── ...
│   │
│   ├── Utils/
│   │   ├── Logger.php
│   │   └── ErrorHandler.php
│   │
│   └── Tests/         # Otomasyon testleri
│       ├── Unit/
│       └── Integration/
│
├── resources/         # Kaynak dosyaları
│   ├── views/         # Template ve view dosyaları
│   ├── lang/          # Çok dil destekli uygulamalar için dil dosyaları
│   └── assets/        # CSS, JavaScript, resimler, vb.
│
├── public/            # Genel erişime açık statik dosyalar ve index.php
│   ├── img/
│   ├── css/
│   ├── js/
│   └── index.php
│
├── storage/           # Geçici dosyalar (örn. önbellek, loglar)
│   ├── cache/
│   ├── logs/
│   └── sessions/
│
├── .htaccess
├── error.log
├── autoload.php       # PSR-4 autoload
└── README.md          # Proje dokümantasyonu

Yukarıdaki yapıda, MVC yaklaşımını benimseyerek klasör yapısını organize ettim. Bununla birlikte, diğer best practices (en iyi uygulamalar) ilkesini de gözeterek birkaç ekstra klasör ekledim.

Bu yapı, modern PHP uygulamalarında yaygın olarak kullanılan bir yapıdır ve hem web tabanlı uygulamalar hem de API servisleri için uygundur. Ancak bu yapı, özellikle büyük ölçekli uygulamalar için daha uygun olabilir. Eğer projeniz daha küçük ölçekli ve minimalist bir yapı istiyorsa, bazı klasörleri ve alt sistemleri kaldırabilirsiniz.

Son olarak, bu yapıyı kullanırken composer ve PSR-4 otomatik yükleme (autoload) özelliklerini kullanmanızı öneririm. Bu sayede sınıflarınızı kolayca yönetebilir ve organize edebilirsiniz.

- LeanPress/
  - data      // SQL vs.
  - install   // data klasörndeki SQL vs. ile kurulum için gerekli komutlar var, kurulum bitince kaldırılacak.
  - src/
    - Core
      - Auth
        - AuthMiddleware.php
      - Router
        - Router.php
        - Request.php
        - Response.php
      - ...
    - Model/
      - BaseModel.php
      - AuthModel.php
      - UserModel.php
      - ...
    - Controller/
      - AuthController.php
      - UserController.php
      - ...
    - Heppers
      - JWTHelper.php
    - Utils/
      - Logger.php
      - ErrorHandler.php
  - .htaccess
  - error.log
  - index.php
  - config.ini
  - autoload.php
  - readme.txt