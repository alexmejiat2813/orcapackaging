
IF NOT EXISTS (SELECT * FROM sys.tables WHERE name = 'Requis_Production_Status')
BEGIN
    PRINT '🔧 Tabla Requis_Production_Status no existe. Creando...'
    CREATE TABLE ThomasOrca.dbo.Requis_Production_Status (
        Requis_Production_Status_Id INT PRIMARY KEY IDENTITY(1,1),
        Requis_Id INT,
        Production_Status_Id INT
    );
END
ELSE
BEGIN
    PRINT '✅ Tabla Requis_Production_Status ya existe. Validando integridad...'

    -- Eliminar registros inválidos
    DELETE FROM ThomasOrca.dbo.Requis_Production_Status
    WHERE Requis_Id IS NULL
       OR Requis_Id NOT IN (SELECT Requis_Id FROM ThomasOrca.dbo.Requis);

    -- Modificar columnas a NOT NULL
    ALTER TABLE ThomasOrca.dbo.Requis_Production_Status
    ALTER COLUMN Requis_Id INT NOT NULL;

    ALTER TABLE ThomasOrca.dbo.Requis_Production_Status
    ALTER COLUMN Production_Status_Id INT NOT NULL;

    -- Agregar clave primaria si no existe
    IF NOT EXISTS (
        SELECT * FROM sys.key_constraints
        WHERE name = 'PK_Requis_Production_Status'
    )
    BEGIN
        ALTER TABLE ThomasOrca.dbo.Requis_Production_Status
        ADD CONSTRAINT PK_Requis_Production_Status
        PRIMARY KEY (Requis_Id, Production_Status_Id);
    END

    -- Agregar foreign key si no existe
    IF NOT EXISTS (
        SELECT * FROM sys.foreign_keys
        WHERE name = 'FK_Requis_ProdStatus_Requis'
    )
    BEGIN
        ALTER TABLE ThomasOrca.dbo.Requis_Production_Status
        ADD CONSTRAINT FK_Requis_ProdStatus_Requis
        FOREIGN KEY (Requis_Id) REFERENCES ThomasOrca.dbo.Requis(Requis_Id);
    END
END
