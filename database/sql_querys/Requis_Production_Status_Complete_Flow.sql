
-- =============================================
-- FLUJO COMPLETO PARA: Requis_Production_status_complete
-- Incluye validación, limpieza y creación de claves foráneas
-- =============================================

-- 1. ELIMINAR REGISTROS INVÁLIDOS

-- ⚠️ Eliminamos si Follow_Operation_Id no existe
DELETE rpsc
FROM ThomasOrca.dbo.Requis_Production_status_complete rpsc
LEFT JOIN ThomasOrca.dbo.Requis r ON r.Requis_Id = rpsc.Follow_Operation_Id
WHERE r.Requis_Id IS NULL;

-- ⚠️ Eliminamos si Closed_Operation_Id no existe
DELETE rpsc
FROM ThomasOrca.dbo.Requis_Production_status_complete rpsc
LEFT JOIN ThomasOrca.dbo.Requis r ON r.Requis_Id = rpsc.Closed_Operation_Id
WHERE r.Requis_Id IS NULL;

-- ⚠️ Eliminamos si Follow_Production_Status_Id no existe
DELETE rpsc
FROM ThomasOrca.dbo.Requis_Production_status_complete rpsc
LEFT JOIN ThomasOrca.dbo.Production_Status ps ON ps.Production_Status_Id = rpsc.Follow_Production_Status_Id
WHERE ps.Production_Status_Id IS NULL;

-- ⚠️ Eliminamos si Follow_Type_Id no existe
DELETE rpsc
FROM ThomasOrca.dbo.Requis_Production_status_complete rpsc
LEFT JOIN ThomasOrca.dbo.Follow_Type ft ON ft.Follow_Type_Id = rpsc.Follow_Type_Id
WHERE ft.Follow_Type_Id IS NULL;

-- ⚠️ Eliminamos si Closed_Type_Id no existe
DELETE rpsc
FROM ThomasOrca.dbo.Requis_Production_status_complete rpsc
LEFT JOIN ThomasOrca.dbo.Follow_Type ft ON ft.Follow_Type_Id = rpsc.Closed_Type_Id
WHERE ft.Follow_Type_Id IS NULL;

-- 2. CONVERTIR COLUMNAS EN NOT NULL

ALTER TABLE ThomasOrca.dbo.Requis_Production_status_complete
ALTER COLUMN Follow_Operation_Id INT NOT NULL;

ALTER TABLE ThomasOrca.dbo.Requis_Production_status_complete
ALTER COLUMN Closed_Operation_Id INT NOT NULL;

ALTER TABLE ThomasOrca.dbo.Requis_Production_status_complete
ALTER COLUMN Follow_Production_Status_Id INT NOT NULL;

ALTER TABLE ThomasOrca.dbo.Requis_Production_status_complete
ALTER COLUMN Follow_Type_Id INT NOT NULL;

ALTER TABLE ThomasOrca.dbo.Requis_Production_status_complete
ALTER COLUMN Closed_Type_Id INT NOT NULL;

-- 3. AÑADIR CLAVES FORÁNEAS (si no existen)

-- FK: Follow_Operation_Id
IF NOT EXISTS (
    SELECT 1 FROM sys.foreign_keys WHERE name = 'FK_ProdComplete_FollowOp'
)
BEGIN
    ALTER TABLE ThomasOrca.dbo.Requis_Production_status_complete
    ADD CONSTRAINT FK_ProdComplete_FollowOp
    FOREIGN KEY (Follow_Operation_Id)
    REFERENCES Requis(Requis_Id);
END;

-- FK: Closed_Operation_Id
IF NOT EXISTS (
    SELECT 1 FROM sys.foreign_keys WHERE name = 'FK_ProdComplete_ClosedOp'
)
BEGIN
    ALTER TABLE ThomasOrca.dbo.Requis_Production_status_complete
    ADD CONSTRAINT FK_ProdComplete_ClosedOp
    FOREIGN KEY (Closed_Operation_Id)
    REFERENCES Requis(Requis_Id);
END;

-- FK: Follow_Production_Status_Id
IF NOT EXISTS (
    SELECT 1 FROM sys.foreign_keys WHERE name = 'FK_ProdComplete_FollowStatus'
)
BEGIN
    ALTER TABLE ThomasOrca.dbo.Requis_Production_status_complete
    ADD CONSTRAINT FK_ProdComplete_FollowStatus
    FOREIGN KEY (Follow_Production_Status_Id)
    REFERENCES Production_Status(Production_Status_Id);
END;

-- FK: Follow_Type_Id
IF NOT EXISTS (
    SELECT 1 FROM sys.foreign_keys WHERE name = 'FK_ProdComplete_FollowType'
)
BEGIN
    ALTER TABLE ThomasOrca.dbo.Requis_Production_status_complete
    ADD CONSTRAINT FK_ProdComplete_FollowType
    FOREIGN KEY (Follow_Type_Id)
    REFERENCES Follow_Type(Follow_Type_Id);
END;

-- FK: Closed_Type_Id
IF NOT EXISTS (
    SELECT 1 FROM sys.foreign_keys WHERE name = 'FK_ProdComplete_ClosedType'
)
BEGIN
    ALTER TABLE ThomasOrca.dbo.Requis_Production_status_complete
    ADD CONSTRAINT FK_ProdComplete_ClosedType
    FOREIGN KEY (Closed_Type_Id)
    REFERENCES Follow_Type(Follow_Type_Id);
END;
