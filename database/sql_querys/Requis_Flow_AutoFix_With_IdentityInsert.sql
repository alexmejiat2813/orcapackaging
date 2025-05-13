
-- ===================================================
-- FLUJO COMPLETO Y AUTOMÁTICO (versión corregida):
-- Relaciones + Corrección de registros huérfanos
-- con soporte para columnas IDENTITY
-- ===================================================

-- 🔍 Verificar y corregir registros inválidos en Requis_Planified_From → Equipment_Regroupement

IF EXISTS (
    SELECT 1
    FROM ThomasOrca.dbo.Requis_Planified_From rpf
    LEFT JOIN ThomasOrca.dbo.Equipment_Regroupement er
        ON rpf.Equipment_Regroupement_ID = er.Equipment_Regroupement_ID
    WHERE er.Equipment_Regroupement_ID IS NULL
)
BEGIN
    PRINT '⚠️ Requis_Planified_From contiene registros inválidos para Equipment_Regroupement.';

    SELECT DISTINCT rpf.Equipment_Regroupement_ID
    INTO #Regroupement_Missing
    FROM ThomasOrca.dbo.Requis_Planified_From rpf
    WHERE rpf.Equipment_Regroupement_ID NOT IN (
        SELECT Equipment_Regroupement_ID FROM ThomasOrca.dbo.Equipment_Regroupement
    );

    DECLARE @regroupementId INT;
    DECLARE regroupement_cursor CURSOR FOR
        SELECT Equipment_Regroupement_ID FROM #Regroupement_Missing;

    OPEN regroupement_cursor;
    FETCH NEXT FROM regroupement_cursor INTO @regroupementId;

    SET IDENTITY_INSERT ThomasOrca.dbo.Equipment_Regroupement ON;

    WHILE @@FETCH_STATUS = 0
    BEGIN
        INSERT INTO ThomasOrca.dbo.Equipment_Regroupement (
            Equipment_Regroupement_ID,
            Equipment_Regroupement_Code,
            Equipment_Regroupement_Description,
            Equipment_Regroupement_Active
        )
        VALUES (
            @regroupementId,
            CONCAT('AUTO_', @regroupementId),
            'Auto-generated placeholder regroupement',
            0
        );

        FETCH NEXT FROM regroupement_cursor INTO @regroupementId;
    END;

    SET IDENTITY_INSERT ThomasOrca.dbo.Equipment_Regroupement OFF;

    CLOSE regroupement_cursor;
    DEALLOCATE regroupement_cursor;
    DROP TABLE #Regroupement_Missing;
END
ELSE
BEGIN
    PRINT '✅ Equipment_Regroupement_ID está limpio.';
END

IF NOT EXISTS (
    SELECT * FROM sys.foreign_keys WHERE name = 'FK_RequisPlanified_EquipmentRegroupement'
)
BEGIN
    ALTER TABLE ThomasOrca.dbo.Requis_Planified_From
    ADD CONSTRAINT FK_RequisPlanified_EquipmentRegroupement
    FOREIGN KEY (Equipment_Regroupement_ID)
    REFERENCES ThomasOrca.dbo.Equipment_Regroupement(Equipment_Regroupement_ID);
    PRINT '✅ FK_RequisPlanified_EquipmentRegroupement creada.';
END


-- 🔍 Validación Follow_Condition
IF EXISTS (
    SELECT 1
    FROM ThomasOrca.dbo.Requis_Condition rc
    LEFT JOIN ThomasOrca.dbo.Follow_Condition fc
        ON rc.Follow_Condition_Id = fc.Follow_Condition_Id
    WHERE fc.Follow_Condition_Id IS NULL
)
BEGIN
    PRINT '⚠️ Requis_Condition contiene registros inválidos para Follow_Condition.';

    SELECT DISTINCT rc.Follow_Condition_Id
    INTO #Follow_Missing
    FROM ThomasOrca.dbo.Requis_Condition rc
    WHERE rc.Follow_Condition_Id NOT IN (
        SELECT Follow_Condition_Id FROM ThomasOrca.dbo.Follow_Condition
    );

    DECLARE @followId INT;
    DECLARE follow_cursor CURSOR FOR
        SELECT Follow_Condition_Id FROM #Follow_Missing;

    OPEN follow_cursor;
    FETCH NEXT FROM follow_cursor INTO @followId;

    WHILE @@FETCH_STATUS = 0
    BEGIN
        INSERT INTO ThomasOrca.dbo.Follow_Condition (
            Follow_Condition_Id,
            Follow_Condition_Description,
            Follow_Condition_Report,
            Follow_Condition_Name,
            Follow_Condition_Active
        )
        VALUES (
            @followId,
            'Auto-generated placeholder',
            'Auto',
            CONCAT('AUTO_', @followId),
            0
        );

        FETCH NEXT FROM follow_cursor INTO @followId;
    END;

    CLOSE follow_cursor;
    DEALLOCATE follow_cursor;
    DROP TABLE #Follow_Missing;
END
ELSE
BEGIN
    PRINT '✅ Follow_Condition_Id está limpio.';
END

IF NOT EXISTS (
    SELECT * FROM sys.foreign_keys WHERE name = 'FK_RequisCondition_FollowCondition'
)
BEGIN
    ALTER TABLE ThomasOrca.dbo.Requis_Condition
    ADD CONSTRAINT FK_RequisCondition_FollowCondition
    FOREIGN KEY (Follow_Condition_Id)
    REFERENCES ThomasOrca.dbo.Follow_Condition(Follow_Condition_Id);
    PRINT '✅ FK_RequisCondition_FollowCondition creada.';
END
