-- Requis
-- Contiene todos los requisitos de producción (materiales, tareas, validaciones, etc.) definidos por código, descripción y estado.
CREATE TABLE Requis (
    Requis_Id INT PRIMARY KEY IDENTITY(1,1),
    Requis_Code NVARCHAR(50),
    Requis_Description NVARCHAR(255),
    Requis_Description_English NVARCHAR(255),
    Requis_Department_Id INT,
    Requis_isFollow BIT,
    Requis_Actif BIT,
    Color_Prerequisites_Missing BIT,
    Requis_isPlanification BIT
);

-- Requis_Planified_From
-- Relaciona un `Requis` con una operación, tipo de producto, y equipo específico donde se necesita.
CREATE TABLE Requis_Planified_From (
    Requis_Planified_From_ID INT PRIMARY KEY IDENTITY(1,1),
    Requis_Id INT,
    Operation_Id INT,
    Equipment_Id INT,
    Type_Id INT
);

-- Relación 1: Requis_Planified_From → Requis
ALTER TABLE ThomasOrca.dbo.Requis_Planified_From
ADD CONSTRAINT FK_Requis_Planified_From_Requis
FOREIGN KEY (Requis_Id) REFERENCES ThomasOrca.dbo.Requis(Requis_Id);

-- Requis_Condition
-- Define condiciones lógicas para que un `Requis` sea obligatorio (ej. si hay impresión, si es wicket, etc.).
CREATE TABLE Requis_Condition (
    Requis_Condition_Id INT PRIMARY KEY IDENTITY(1,1),
    Requis_Id INT,
    Follow_Condition_Id INT,
    Active BIT
);

-- Relación 2: Requis_Condition → Requis
ALTER TABLE ThomasOrca.dbo.Requis_Condition
ADD CONSTRAINT FK_Requis_Condition_Requis
FOREIGN KEY (Requis_Id) REFERENCES ThomasOrca.dbo.Requis(Requis_Id);

-- Requis_Production_Status
-- Define en qué estado de producción debe estar activo o validado un `Requis`.
CREATE TABLE Requis_Production_Status (
    Requis_Production_Status_Id INT PRIMARY KEY IDENTITY(1,1),
    Requis_Id INT,
    Production_Status_Id INT
);

ALTER TABLE Requis_Production_Status
ALTER COLUMN Requis_Id INT NOT NULL;

ALTER TABLE Requis_Production_Status
ALTER COLUMN Production_Status_Id INT NOT NULL;


ALTER TABLE ThomasOrca.dbo.Requis_Production_Status
ADD CONSTRAINT PK_Requis_Production_Status
PRIMARY KEY (Requis_Id, Production_Status_Id);


-- Relación 3: Requis_Production_Status → Requis
ALTER TABLE ThomasOrca.dbo.Requis_Production_Status
ADD CONSTRAINT FK_Requis_ProdStatus_Requis
FOREIGN KEY (Requis_Id) REFERENCES ThomasOrca.dbo.Requis(Requis_Id);

-- Requis_Estimation
-- Almacena estimaciones de cantidad por Requis, incluyendo unidad de medida y código de fórmula si aplica.
CREATE TABLE Requis_Estimation (
    Requis_Estimation_Id INT PRIMARY KEY IDENTITY(1,1),
    Requis_Id INT,
    Unit_Measurement_Id INT,
    Code_Estimation NVARCHAR(255)
);

-- Relación 4: Requis_Estimation → Requis
ALTER TABLE Requis_Estimation
ADD CONSTRAINT FK_Requis_Estimation_Requis
FOREIGN KEY (Requis_Id) REFERENCES Requis(Requis_Id);

-- Requis_Production_status_complete
-- Controla relaciones entre operaciones de producción: cuándo una operación completa activa o desbloquea otra.
CREATE TABLE Requis_Production_status_complete (
    Requis_Production_Status_Complete_Id INT PRIMARY KEY IDENTITY(1,1),
    Closed_Operation_Id INT,
    Closed_Type_Id INT,
    Follow_Operation_Id INT,
    Follow_Type_Id INT,
    Follow_Production_Status_Id INT
);


-- Relación 5: Requis_Production_status_complete → Requis
ALTER TABLE Requis_Production_status_complete
ADD CONSTRAINT FK_Requis_ProdStatusComplete_Requis
FOREIGN KEY (Follow_Operation_Id) REFERENCES Requis(Requis_Id);


ALTER TABLE Requis_Production_status_complete
ADD CONSTRAINT FK_Requis_ProdStatusClosed_Requis
FOREIGN KEY (Closed_Operation_Id) REFERENCES Requis(Requis_Id);
