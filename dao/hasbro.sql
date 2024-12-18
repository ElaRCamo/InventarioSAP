SELECT
    SUM(
        CASE
            WHEN ISap.Cantidad > COALESCE(

                BInv.PrimerConteo
            , 0.00) OR BInv.NumeroParte IS NULL THEN 1
            ELSE 0
        END
    ) AS 'Cantidad_Total_Negativa',
    SUM(
        CASE
            WHEN ISap.Cantidad < COALESCE(
                CASE WHEN BInv.TercerConteo != 0.00 THEN BInv.TercerConteo END,
                CASE WHEN BInv.SegundoConteo != 0.00 THEN BInv.SegundoConteo END,
                BInv.PrimerConteo
            , 0.00) AND BInv.Estatus = 1 THEN 1
            ELSE 0
        END
    ) AS 'Cantidad_Total_Positiva',
    SUM(
        CASE
            WHEN (ISap.Cantidad * (Parte.Costo / Parte.Por)) >
                 (COALESCE
                 (
                CASE WHEN BInv.TercerConteo != 0.00 THEN BInv.TercerConteo END,
                CASE WHEN BInv.SegundoConteo != 0.00 THEN BInv.SegundoConteo END,
                BInv.PrimerConteo
            , 0.00) *  (Parte.Costo / Parte.Por)

            )
AND BInv.Estatus = 1

                THEN (ISap.Cantidad * (Parte.Costo / Parte.Por)) - (COALESCE(
                CASE WHEN BInv.TercerConteo != 0.00 THEN BInv.TercerConteo END,
                CASE WHEN BInv.SegundoConteo != 0.00 THEN BInv.SegundoConteo END,
                BInv.PrimerConteo
            , 0.00) * (Parte.Costo / Parte.Por))
            ELSE 0
        END
    ) AS 'Costo_Total_Negativo',
    SUM(
        CASE
            WHEN (ISap.Cantidad * (Parte.Costo / Parte.Por)) < (COALESCE(
                CASE WHEN BInv.TercerConteo != 0.00 THEN BInv.TercerConteo END,
                CASE WHEN BInv.SegundoConteo != 0.00 THEN BInv.SegundoConteo END,
                BInv.PrimerConteo
            , 0.00) * (Parte.Costo / Parte.Por)) AND BInv.Estatus = 1 THEN (COALESCE(
                CASE WHEN BInv.TercerConteo != 0.00 THEN BInv.TercerConteo END,
                CASE WHEN BInv.SegundoConteo != 0.00 THEN BInv.SegundoConteo END,
                BInv.PrimerConteo
            , 0.00) * (Parte.Costo / Parte.Por)) - (ISap.Cantidad * (Parte.Costo / Parte.Por))
            ELSE 0
        END
    ) AS 'Costo_Total_Positivo'
FROM
    InventarioSap ISap
LEFT JOIN
    Bitacora_Inventario BInv ON ISap.GrammerNo = BInv.NumeroParte AND ISap.STBin = BInv.StorageBin
JOIN
    Parte ON ISap.GrammerNo = Parte.GrammerNo where Parte.GrammerNo like '1335329-C';