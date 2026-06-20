# UML Diagrams - Sistem Klasifikasi Penerima Bantuan Subsidi Listrik

---

## 1. CLASS DIAGRAM (Database Models & Relationships)

```mermaid
classDiagram
    %% Core Models
    class User {
        -id: int
        -name: string
        -email: string
        -password: string
        -role_id: int
        -is_active: boolean
        -created_at: timestamp
        -updated_at: timestamp
        
        +register()
        +login()
        +logout()
        +updateProfile()
        +deleteAccount()
    }

    class Role {
        -id: int
        -name: string
        -description: string
        -created_at: timestamp
        
        +assignPermission()
        +revokePermission()
        +getPermissions()
    }

    class Permission {
        -id: int
        -name: string
        -key: string
        -description: string
        -created_at: timestamp
        
        +getName()
        +getKey()
    }

    class ActivityLog {
        -id: int
        -user_id: int
        -action: string
        -description: string
        -subject_type: string
        -subject_id: int
        -data: json
        -created_at: timestamp
        
        +logActivity()
        +getActivityByUser()
        +getActivityByAction()
    }

    %% Data Management Models
    class Atribut {
        -id: int
        -name: string
        -slug: string
        -type: enum (numerik, kategorikal)
        -description: string
        -created_at: timestamp
        
        +create()
        +update()
        +delete()
        +getNilaiAtribut()
    }

    class NilaiAtribut {
        -id: int
        -atribut_id: int
        -value: string
        -created_at: timestamp
        
        +addValue()
        +updateValue()
        +deleteValue()
    }

    class TrainingData {
        -id: int
        -customer_id: string
        -name: string
        -attributes: json
        -status: enum (Layak, Tidak Layak)
        -created_at: timestamp
        
        +create()
        +import()
        +export()
        +getAll()
        +clear()
    }

    class TestingData {
        -id: int
        -customer_id: string
        -name: string
        -attributes: json
        -actual_status: enum (Layak, Tidak Layak)
        -created_at: timestamp
        
        +create()
        +import()
        +export()
        +getAll()
        +clear()
    }

    class Probability {
        -id: int
        -atribut_id: int
        -value: string
        -class: enum (Layak, Tidak Layak)
        -probability: float
        -count: int
        -created_at: timestamp
        
        +calculate()
        +getPrior()
        +getLikelihood()
        +store()
    }

    class Classification {
        -id: int
        -data_type: enum (training, testing)
        -training_data_id: int
        -predicted_class: enum (Layak, Tidak Layak)
        -confidence: float
        -details: json
        -created_at: timestamp
        
        +runClassification()
        +getDetail()
        +calculateConfidence()
        +export()
    }

    %% Relationships
    User "1" --> "many" ActivityLog : "melakukan"
    User "many" --> "1" Role : "memiliki"
    Role "many" --> "many" Permission : "memiliki"
    
    Atribut "1" --> "many" NilaiAtribut : "memiliki"
    Atribut "1" --> "many" Probability : "digunakan_untuk"
    
    TrainingData "1" --> "many" Classification : "dijalankan_klasifikasi"
    TestingData "1" --> "many" Classification : "dijalankan_klasifikasi"
    
    Probability "many" --> "1" Atribut : "untuk_atribut"

```

---

## 2. USE CASE DIAGRAM (Fitur & Aktor)

```mermaid
graph TB
    subgraph Users["👥 USERS"]
        Admin["Admin"]
        Analyst["Data Analyst"]
        Operator["Data Operator"]
    end

    subgraph Auth["🔐 AUTHENTICATION"]
        UC1["Register"]
        UC2["Login"]
        UC3["Logout"]
        UC4["Reset Password"]
    end

    subgraph AdminFeatures["👨‍💼 ADMIN FEATURES"]
        UC5["Manage Users"]
        UC6["Manage Roles & Permissions"]
        UC7["View Activity Logs"]
        UC8["Toggle User Status"]
        UC9["Edit User Data"]
    end

    subgraph DataMgmt["📊 DATA MANAGEMENT"]
        UC10["Manage Attributes"]
        UC11["Input Training Data"]
        UC12["Import Training Data"]
        UC13["Input Testing Data"]
        UC14["Import Testing Data"]
        UC15["Export Data"]
        UC16["Clear Data"]
    end

    subgraph Classification["🤖 CLASSIFICATION"]
        UC17["Calculate Probability"]
        UC18["Run Classification"]
        UC19["View Classification Results"]
        UC20["View Classification Details"]
        UC21["Reset Classification"]
    end

    subgraph Reports["📈 REPORTS"]
        UC22["View Performance Metrics"]
        UC23["View Classification Report"]
        UC24["Export Report CSV"]
        UC25["Export Report Excel"]
        UC26["Export Report PDF"]
    end

    subgraph Profile["👤 USER PROFILE"]
        UC27["View Profile"]
        UC28["Edit Profile"]
        UC29["Delete Account"]
    end

    subgraph Security["🔒 SECURITY"]
        UC30["Permission Check"]
        UC31["Activity Logging"]
        UC32["User Status Control"]
    end

    Admin --> UC5
    Admin --> UC6
    Admin --> UC7
    Admin --> UC8
    Admin --> UC9
    Admin --> UC30
    
    Analyst --> UC1
    Analyst --> UC2
    Analyst --> UC3
    Analyst --> UC4
    Analyst --> UC10
    Analyst --> UC11
    Analyst --> UC12
    Analyst --> UC13
    Analyst --> UC14
    Analyst --> UC15
    Analyst --> UC16
    Analyst --> UC17
    Analyst --> UC18
    Analyst --> UC19
    Analyst --> UC20
    Analyst --> UC21
    Analyst --> UC22
    Analyst --> UC23
    Analyst --> UC24
    Analyst --> UC25
    Analyst --> UC26
    Analyst --> UC27
    Analyst --> UC28
    Analyst --> UC29
    
    Operator --> UC1
    Operator --> UC2
    Operator --> UC3
    Operator --> UC4
    Operator --> UC11
    Operator --> UC12
    Operator --> UC13
    Operator --> UC14
    Operator --> UC15
    Operator --> UC27
    Operator --> UC28

    style Auth fill:#e1f5ff
    style AdminFeatures fill:#fff3e0
    style DataMgmt fill:#f3e5f5
    style Classification fill:#e8f5e9
    style Reports fill:#fce4ec
    style Profile fill:#f1f8e9
    style Security fill:#ffe0b2

```

---

## 3. ENTITY RELATIONSHIP DIAGRAM (Database Schema)

```mermaid
erDiagram
    USERS ||--o{ ROLES : "belongs_to"
    USERS ||--o{ ACTIVITY_LOGS : "has_many"
    USERS ||--o{ CLASSIFICATIONS : "created_by"
    
    ROLES ||--o{ PERMISSIONS : "has_many"
    ROLES ||--o{ ROLE_PERMISSIONS : "has_many"
    PERMISSIONS ||--o{ ROLE_PERMISSIONS : "has_many"
    
    ATTRIBUTES ||--o{ NILAI_ATTRIBUTES : "has_many"
    ATTRIBUTES ||--o{ PROBABILITIES : "has_many"
    
    TRAINING_DATA ||--o{ CLASSIFICATIONS : "has_many"
    TESTING_DATA ||--o{ CLASSIFICATIONS : "has_many"
    
    PROBABILITIES }o--|| ATTRIBUTES : "for_attribute"

    USERS {
        int id PK
        string name
        string email UK
        string password
        int role_id FK
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    ROLES {
        int id PK
        string name
        string description
        timestamp created_at
    }

    PERMISSIONS {
        int id PK
        string name
        string key UK
        string description
        timestamp created_at
    }

    ROLE_PERMISSIONS {
        int role_id FK PK
        int permission_id FK PK
    }

    ACTIVITY_LOGS {
        int id PK
        int user_id FK
        string action
        string description
        string subject_type
        int subject_id
        json data
        timestamp created_at
    }

    ATTRIBUTES {
        int id PK
        string name UK
        string slug UK
        enum type
        string description
        timestamp created_at
    }

    NILAI_ATTRIBUTES {
        int id PK
        int atribut_id FK
        string value
        timestamp created_at
    }

    TRAINING_DATA {
        int id PK
        string customer_id UK
        string name
        json attributes
        enum status
        timestamp created_at
    }

    TESTING_DATA {
        int id PK
        string customer_id UK
        string name
        json attributes
        enum actual_status
        timestamp created_at
    }

    CLASSIFICATIONS {
        int id PK
        int training_data_id FK
        int testing_data_id FK
        enum data_type
        enum predicted_class
        float confidence
        json details
        timestamp created_at
    }

    PROBABILITIES {
        int id PK
        int atribut_id FK
        string value
        enum class
        float probability
        int count
        timestamp created_at
    }

```

---

## 4. SEQUENCE DIAGRAM - Proses Classification

```mermaid
sequenceDiagram
    participant User as User/Analyst
    participant System as System
    participant DB as Database
    participant NB as Naive Bayes Engine

    User->>System: 1. Upload Training Data
    System->>DB: Simpan ke training_data
    DB-->>System: ✓ Data saved
    System-->>User: ✓ Success

    User->>System: 2. Calculate Probability
    System->>DB: Ambil training_data
    DB-->>System: ✓ Data
    System->>NB: Hitung P(Layak), P(Tidak Layak)
    NB->>NB: Hitung prior probability
    NB->>NB: Hitung likelihood untuk setiap atribut
    NB-->>System: ✓ Hasil probabilitas
    System->>DB: Simpan ke probabilities table
    DB-->>System: ✓ Saved
    System-->>User: ✓ Probability calculated

    User->>System: 3. Upload Testing Data
    System->>DB: Simpan ke testing_data
    DB-->>System: ✓ Data saved
    System-->>User: ✓ Success

    User->>System: 4. Run Classification
    System->>DB: Ambil testing_data
    DB-->>System: ✓ Test data
    System->>DB: Ambil probabilities
    DB-->>System: ✓ Probabilities
    System->>NB: Klasifikasi setiap record
    loop Untuk setiap testing record
        NB->>NB: Hitung posterior probability
        NB->>NB: Bandingkan P(Layak) vs P(Tidak Layak)
        NB-->>System: Predicted class + confidence
    end
    System->>DB: Simpan hasil ke classifications
    DB-->>System: ✓ Saved
    System-->>User: ✓ Classification complete

    User->>System: 5. View Results & Metrics
    System->>DB: Ambil classifications
    DB-->>System: ✓ Results
    System->>System: Hitung: Accuracy, Precision, Recall
    System-->>User: ✓ Display metrics & report

```

---

## 5. SEQUENCE DIAGRAM - Admin User Management

```mermaid
sequenceDiagram
    participant Admin as Admin User
    participant System as System
    participant Auth as Auth Service
    participant DB as Database
    participant Logger as Activity Logger

    Admin->>System: 1. Access /admin/accounts/users
    System->>Auth: Check permission: manage_users
    Auth-->>System: ✓ Authorized
    System->>DB: Fetch semua users
    DB-->>System: ✓ User list
    System-->>Admin: ✓ Display user list

    Admin->>System: 2. Edit user: nama/email/role
    System->>Auth: Check permission: manage_users
    Auth-->>System: ✓ Authorized
    System->>System: Validasi input
    System->>DB: Update user table
    DB-->>System: ✓ Updated
    System->>Logger: Log activity: user.updated
    Logger->>DB: Simpan activity log
    DB-->>Logger: ✓ Logged
    System-->>Admin: ✓ User updated

    Admin->>System: 3. Toggle user status
    System->>Auth: Check permission: manage_users
    Auth-->>System: ✓ Authorized
    System->>System: Cek: tidak boleh nonaktifkan diri sendiri
    System->>DB: Update is_active field
    DB-->>System: ✓ Updated
    System->>Logger: Log activity: user.activated/deactivated
    Logger->>DB: Simpan activity log
    DB-->>Logger: ✓ Logged
    System-->>Admin: ✓ Status toggled

    Admin->>System: 4. Manage Permissions
    System->>Auth: Check permission: manage_role_permissions
    Auth-->>System: ✓ Authorized
    System->>DB: Fetch roles & permissions
    DB-->>System: ✓ Roles & Permissions
    System-->>Admin: ✓ Display permission matrix

    Admin->>System: 5. Update role permissions
    System->>Auth: Check: tidak boleh edit admin role
    Auth-->>System: ✓ Can edit
    System->>DB: Update role_permissions table
    DB-->>System: ✓ Updated
    System->>Logger: Log activity: role.permissions_updated
    Logger->>DB: Simpan activity log
    DB-->>Logger: ✓ Logged
    System-->>Admin: ✓ Permissions updated

    Admin->>System: 6. View Activity Logs
    System->>Auth: Check permission: view_activity_logs
    Auth-->>System: ✓ Authorized
    System->>DB: Query activity_logs dengan filter
    DB-->>System: ✓ Activity logs
    System-->>Admin: ✓ Display logs

```

---

## 6. ACTIVITY FLOW DIAGRAM

```mermaid
graph TD
    Start([User Login]) --> CheckActive{User Active?}
    CheckActive -->|No| Denied["❌ Access Denied<br/>Akun tidak aktif"]
    Denied --> End1([Logout])
    
    CheckActive -->|Yes| Menu["📋 Main Menu"]
    
    Menu --> Auth{"Role &<br/>Permission?"}
    Auth -->|User| UserMenu["👤 User Options"]
    Auth -->|Analyst| AnalystMenu["📊 Analyst Options"]
    Auth -->|Admin| AdminMenu["👨‍💼 Admin Options"]
    
    UserMenu --> UP1["Edit Profile"]
    UserMenu --> UP2["Logout"]
    UP1 --> Activity1["→ Log: user.updated"]
    UP2 --> Activity2["→ Log: user.logout"]
    
    AnalystMenu --> AP1["Manage Data"]
    AnalystMenu --> AP2["Run Classification"]
    AnalystMenu --> AP3["View Reports"]
    AP1 --> Activity3["→ Log: data operation"]
    AP2 --> Activity4["→ Log: classification"]
    AP3 --> Activity5["→ Log: view_reports"]
    
    AdminMenu --> AD1["Manage Users"]
    AdminMenu --> AD2["Manage Permissions"]
    AdminMenu --> AD3["View Activity Logs"]
    AD1 --> Activity6["→ Log: user.updated/activated/deactivated"]
    AD2 --> Activity7["→ Log: role.permissions_updated"]
    AD3 --> Activity8["→ View all activities"]
    
    Activity1 --> DB[(Database)]
    Activity2 --> DB
    Activity3 --> DB
    Activity4 --> DB
    Activity5 --> DB
    Activity6 --> DB
    Activity7 --> DB
    Activity8 --> DB
    
    DB --> End2([Logout])

    style Denied fill:#ffcdd2
    style UserMenu fill:#e3f2fd
    style AnalystMenu fill:#f3e5f5
    style AdminMenu fill:#fff3e0
    style DB fill:#e8f5e9

```

---

## 7. NAIVE BAYES CLASSIFICATION FLOW

```mermaid
graph LR
    Start([Training Data]) --> Step1["Step 1: Hitung Prior Probability<br/>P(Layak) = Jumlah Layak / Total<br/>P(Tidak Layak) = Jumlah Tidak Layak / Total"]
    
    Step1 --> Step2["Step 2: Hitung Likelihood<br/>untuk setiap atribut nilai"]
    
    Step2 --> Step3["Step 3: Simpan hasil ke<br/>Probability Table"]
    
    Step3 --> TestStart([Testing Data])
    
    TestStart --> Step4["Step 4: Ambil test record"]
    
    Step4 --> Step5["Step 5: Hitung Posterior<br/>P(Layak|X) = P(X|Layak) × P(Layak)<br/>P(Tidak Layak|X) = P(X|Tidak Layak) × P(Tidak Layak)"]
    
    Step5 --> Step6{"Posterior<br/>Layak > Tidak Layak?"}
    
    Step6 -->|Yes| Predict1["Prediksi: LAYAK<br/>Confidence: % nilai Layak"]
    Step6 -->|No| Predict2["Prediksi: TIDAK LAYAK<br/>Confidence: % nilai Tidak Layak"]
    
    Predict1 --> Save["Simpan ke Classification Table"]
    Predict2 --> Save
    
    Save --> NextRec{"Record<br/>selanjutnya?"}
    NextRec -->|Yes| Step4
    NextRec -->|No| Calculate["Hitung Metrik Performa:<br/>Accuracy, Precision, Recall"]
    
    Calculate --> Report["Generate Report"]
    
    Report --> End([Done])
    
    style Start fill:#c8e6c9
    style TestStart fill:#c8e6c9
    style Predict1 fill:#bbdefb
    style Predict2 fill:#ffccbc
    style Calculate fill:#f8bbd0
    style Report fill:#ffe0b2
    style End fill:#c8e6c9

```

---

## 8. SYSTEM ARCHITECTURE DIAGRAM

```mermaid
graph TB
    subgraph Frontend["🖥️ FRONTEND (User Interface)"]
        WEB["Laravel Blade Templates"]
        JS["JavaScript/Ajax"]
        CSS["Bootstrap CSS"]
    end

    subgraph Application["⚙️ APPLICATION LAYER"]
        Router["Route Handler"]
        Controller["Controllers"]
        Auth["Authentication<br/>& Authorization"]
        Middleware["Middleware"]
    end

    subgraph Business["🧠 BUSINESS LOGIC"]
        ClassifyEngine["Classification Engine<br/>(Naive Bayes)"]
        DataService["Data Service"]
        ProbService["Probability Service"]
        ReportService["Report Service"]
        ActivityService["Activity Logger"]
    end

    subgraph Data["💾 DATA LAYER"]
        ORM["Eloquent ORM"]
        Models["Models"]
        DB[(MySQL<br/>Database)]
    end

    subgraph External["🔌 EXTERNAL"]
        Excel["Excel/CSV Handler<br/>(Maatwebsite)"]
        PDF["PDF Generator"]
        Email["Email Service"]
    end

    WEB --> Router
    JS --> Router
    
    Router --> Middleware
    Middleware --> Auth
    Auth --> Controller
    
    Controller --> ClassifyEngine
    Controller --> DataService
    Controller --> ProbService
    Controller --> ReportService
    Controller --> ActivityService
    
    ClassifyEngine --> ProbService
    DataService --> Models
    ReportService --> Models
    ActivityService --> Models
    
    Models --> ORM
    ORM --> DB
    
    DataService --> Excel
    ReportService --> PDF
    ActivityService --> Email
    
    style Frontend fill:#e1f5fe
    style Application fill:#f3e5f5
    style Business fill:#e8f5e9
    style Data fill:#fff3e0
    style External fill:#fce4ec

```

---

## 9. STATE DIAGRAM - User Status

```mermaid
stateDiagram-v2
    [*] --> Registered
    
    Registered --> VerifyEmail: Email verification<br/>pending
    VerifyEmail --> Active: Email verified
    VerifyEmail --> Registered: Resend email link
    
    Active --> Inactive: Admin deactivate user
    Inactive --> Active: Admin activate user
    
    Active --> Deleted: User delete account
    Inactive --> Deleted: Admin delete user
    
    Deleted --> [*]
    
    note right of Registered
        Baru registrasi
        Email belum verified
    end note
    
    note right of VerifyEmail
        Menunggu verifikasi email
        Token dalam email expiring
    end note
    
    note right of Active
        User aktif
        Bisa login & akses sistem
    end note
    
    note right of Inactive
        User tidak aktif
        Tidak bisa login
        Admin bisa aktifkan kembali
    end note
    
    note right of Deleted
        User dihapus
        Data tetap di database
    end note

```

---

## 10. DEPLOYMENT ARCHITECTURE

```mermaid
graph LR
    Client["🖥️ Client Browser<br/>Chrome, Firefox, etc"]
    
    subgraph Server["🌐 Web Server"]
        nginx["Nginx/Apache"]
        Laravel["Laravel Application<br/>PHP-FPM"]
    end
    
    subgraph DB["💾 Database"]
        MySQL["MySQL Server"]
        Cache["Redis Cache"]
    end
    
    subgraph Storage["📦 File Storage"]
        Local["Local Storage<br/>/storage folder"]
        Uploads["Uploads<br/>Excel/CSV files"]
    end
    
    subgraph Queue["⏳ Queue Services"]
        Mail["Mail Queue"]
        Jobs["Job Queue"]
    end
    
    External["📧 External Services<br/>SMTP Email"]
    
    Client -->|HTTP/HTTPS| nginx
    nginx --> Laravel
    Laravel --> MySQL
    Laravel --> Cache
    Laravel --> Local
    Laravel --> Uploads
    Laravel --> Mail
    Laravel --> Jobs
    Mail --> External
    
    style Client fill:#bbdefb
    style Server fill:#c8e6c9
    style DB fill:#fff3e0
    style Storage fill:#f8bbd0
    style Queue fill:#ffe0b2
    style External fill:#ffccbc

```

---

## Legenda & Notasi

### Hubungan Dalam Class Diagram
- `"1" --> "many"` : One-to-Many relationship
- `"many" --> "many"` : Many-to-Many relationship
- `"many" --> "1"` : Many-to-One relationship

### Entity Relationship Diagram
- `PK` : Primary Key
- `FK` : Foreign Key
- `UK` : Unique Key

### Use Case Symbols
- 👥 : User/Actor
- 🔐 : Authentication features
- 👨‍💼 : Admin features
- 📊 : Data management
- 🤖 : Classification features
- 📈 : Reports
- 👤 : User profile
- 🔒 : Security

---

## Ringkasan Diagram

| Diagram | Tujuan | Fokus |
|---------|--------|-------|
| Class Diagram | Struktur models & relationships | OOP structure, inheritance, associations |
| Use Case Diagram | Fitur & interaksi pengguna | Actors, use cases, system scope |
| ER Diagram | Struktur database | Tables, fields, constraints, keys |
| Sequence Diagram | Alur proses | Interaction antar komponen over time |
| Activity Flow | Alur aktivitas user | User journey, decision points |
| Classification Flow | Algoritma Naive Bayes | Step-by-step calculation process |
| System Architecture | Komponen sistem | Layers, modules, dependencies |
| State Diagram | Status user | State transitions, conditions |
| Deployment Architecture | Infrastructure | Hardware, servers, services |

---

*Diagram ini dibuat untuk dokumentasi sistem Klasifikasi Penerima Bantuan Subsidi Listrik menggunakan Naive Bayes*
