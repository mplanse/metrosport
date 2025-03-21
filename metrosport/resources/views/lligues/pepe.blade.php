<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>

<body>

    <div class="container my-4">
        <form action="{{route('dia_hora.store')}}" method="POST">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Hora</th>
                        <th>Lun</th>
                        <th>Mar</th>
                        <th>Mié</th>
                        <th>Jue</th>
                        <th>Vie</th>
                        <th>Sáb</th>
                        <th>Dom</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>00:00 - 01:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckLun00">
                                <label class="form-check-label" for="flexCheckLun00"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="25" id="flexCheckMar00">
                                <label class="form-check-label" for="flexCheckMar00"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="49" id="flexCheckMie00">
                                <label class="form-check-label" for="flexCheckMie00"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="73" id="flexCheckJue00">
                                <label class="form-check-label" for="flexCheckJue00"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="97" id="flexCheckVie00">
                                <label class="form-check-label" for="flexCheckVie00"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="121" id="flexCheckSab00">
                                <label class="form-check-label" for="flexCheckSab00"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="145" id="flexCheckDom00">
                                <label class="form-check-label" for="flexCheckDom00"></label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>01:00 - 02:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="2" id="flexCheckLun01">
                                <label class="form-check-label" for="flexCheckLun01"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="26" id="flexCheckMar01">
                                <label class="form-check-label" for="flexCheckMar01"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="50" id="flexCheckMie01">
                                <label class="form-check-label" for="flexCheckMie01"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="74" id="flexCheckJue01">
                                <label class="form-check-label" for="flexCheckJue01"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="98" id="flexCheckVie01">
                                <label class="form-check-label" for="flexCheckVie01"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="122" id="flexCheckSab01">
                                <label class="form-check-label" for="flexCheckSab01"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="146" id="flexCheckDom01">
                                <label class="form-check-label" for="flexCheckDom01"></label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>02:00 - 03:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="3" id="flexCheckLun02">
                                <label class="form-check-label" for="flexCheckLun02"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="27" id="flexCheckMar02">
                                <label class="form-check-label" for="flexCheckMar02"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="51" id="flexCheckMie02">
                                <label class="form-check-label" for="flexCheckMie02"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="75" id="flexCheckJue02">
                                <label class="form-check-label" for="flexCheckJue02"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="99" id="flexCheckVie02">
                                <label class="form-check-label" for="flexCheckVie02"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="123" id="flexCheckSab02">
                                <label class="form-check-label" for="flexCheckSab02"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="147" id="flexCheckDom02">
                                <label class="form-check-label" for="flexCheckDom02"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>03:00 - 04:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="4" id="flexCheckLun03">
                                <label class="form-check-label" for="flexCheckLun03"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="28" id="flexCheckMar03">
                                <label class="form-check-label" for="flexCheckMar03"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="52" id="flexCheckMie03">
                                <label class="form-check-label" for="flexCheckMie03"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="76" id="flexCheckJue03">
                                <label class="form-check-label" for="flexCheckJue03"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="100" id="flexCheckVie03">
                                <label class="form-check-label" for="flexCheckVie03"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="124" id="flexCheckSab03">
                                <label class="form-check-label" for="flexCheckSab03"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="148" id="flexCheckDom03">
                                <label class="form-check-label" for="flexCheckDom03"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>04:00 - 05:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="5" id="flexCheckLun04">
                                <label class="form-check-label" for="flexCheckLun04"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="29" id="flexCheckMar04">
                                <label class="form-check-label" for="flexCheckMar04"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="53" id="flexCheckMie04">
                                <label class="form-check-label" for="flexCheckMie04"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="77" id="flexCheckJue04">
                                <label class="form-check-label" for="flexCheckJue04"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="101" id="flexCheckVie04">
                                <label class="form-check-label" for="flexCheckVie04"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="125" id="flexCheckSab04">
                                <label class="form-check-label" for="flexCheckSab04"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="149" id="flexCheckDom04">
                                <label class="form-check-label" for="flexCheckDom04"></label>
                            </div>
                        </td>
                    </tr>
                    <td>05:00 - 06:00</td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="6" id="flexCheckLun05">
                            <label class="form-check-label" for="flexCheckLun05"></label>
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="30" id="flexCheckMar05">
                            <label class="form-check-label" for="flexCheckMar05"></label>
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="54" id="flexCheckMie05">
                            <label class="form-check-label" for="flexCheckMie05"></label>
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="78" id="flexCheckJue05">
                            <label class="form-check-label" for="flexCheckJue05"></label>
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="102" id="flexCheckVie05">
                            <label class="form-check-label" for="flexCheckVie05"></label>
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="126" id="flexCheckSab05">
                            <label class="form-check-label" for="flexCheckSab05"></label>
                        </div>
                    </td>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="150" id="flexCheckDom05">
                            <label class="form-check-label" for="flexCheckDom05"></label>
                        </div>
                    </td>
                    </tr>
                    <tr>
                        <td>06:00 - 07:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="7" id="flexCheckLun06">
                                <label class="form-check-label" for="flexCheckLun06"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="31" id="flexCheckMar06">
                                <label class="form-check-label" for="flexCheckMar06"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="55" id="flexCheckMie06">
                                <label class="form-check-label" for="flexCheckMie06"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="79" id="flexCheckJue06">
                                <label class="form-check-label" for="flexCheckJue06"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="103" id="flexCheckVie06">
                                <label class="form-check-label" for="flexCheckVie06"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="127" id="flexCheckSab06">
                                <label class="form-check-label" for="flexCheckSab06"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="151" id="flexCheckDom06">
                                <label class="form-check-label" for="flexCheckDom06"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>07:00 - 08:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="8" id="flexCheckLun07">
                                <label class="form-check-label" for="flexCheckLun07"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="32" id="flexCheckMar07">
                                <label class="form-check-label" for="flexCheckMar07"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="56" id="flexCheckMie07">
                                <label class="form-check-label" for="flexCheckMie07"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="80" id="flexCheckJue07">
                                <label class="form-check-label" for="flexCheckJue07"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="104" id="flexCheckVie07">
                                <label class="form-check-label" for="flexCheckVie07"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="128" id="flexCheckSab07">
                                <label class="form-check-label" for="flexCheckSab07"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="152" id="flexCheckDom07">
                                <label class="form-check-label" for="flexCheckDom07"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>08:00 - 09:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="9" id="flexCheckLun08">
                                <label class="form-check-label" for="flexCheckLun08"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="33" id="flexCheckMar08">
                                <label class="form-check-label" for="flexCheckMar08"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="57" id="flexCheckMie08">
                                <label class="form-check-label" for="flexCheckMie08"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="81" id="flexCheckJue08">
                                <label class="form-check-label" for="flexCheckJue08"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="105" id="flexCheckVie08">
                                <label class="form-check-label" for="flexCheckVie08"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="129" id="flexCheckSab08">
                                <label class="form-check-label" for="flexCheckSab08"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="153" id="flexCheckDom08">
                                <label class="form-check-label" for="flexCheckDom08"></label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>09:00 - 10:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="10" id="flexCheckLun09">
                                <label class="form-check-label" for="flexCheckLun09"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="34" id="flexCheckMar09">
                                <label class="form-check-label" for="flexCheckMar09"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="58" id="flexCheckMie09">
                                <label class="form-check-label" for="flexCheckMie09"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="82" id="flexCheckJue09">
                                <label class="form-check-label" for="flexCheckJue09"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="106" id="flexCheckVie09">
                                <label class="form-check-label" for="flexCheckVie09"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="130" id="flexCheckSab09">
                                <label class="form-check-label" for="flexCheckSab09"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="154" id="flexCheckDom09">
                                <label class="form-check-label" for="flexCheckDom09"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>10:00 - 11:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="11" id="flexCheckLun10">
                                <label class="form-check-label" for="flexCheckLun10"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="35" id="flexCheckMar10">
                                <label class="form-check-label" for="flexCheckMar10"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="59" id="flexCheckMie10">
                                <label class="form-check-label" for="flexCheckMie10"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="83" id="flexCheckJue10">
                                <label class="form-check-label" for="flexCheckJue10"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="107" id="flexCheckVie10">
                                <label class="form-check-label" for="flexCheckVie10"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="131" id="flexCheckSab10">
                                <label class="form-check-label" for="flexCheckSab10"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="155" id="flexCheckDom10">
                                <label class="form-check-label" for="flexCheckDom10"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>11:00 - 12:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="12" id="flexCheckLun11">
                                <label class="form-check-label" for="flexCheckLun11"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="36" id="flexCheckMar11">
                                <label class="form-check-label" for="flexCheckMar11"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="60" id="flexCheckMie11">
                                <label class="form-check-label" for="flexCheckMie11"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="84" id="flexCheckJue11">
                                <label class="form-check-label" for="flexCheckJue11"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="108" id="flexCheckVie11">
                                <label class="form-check-label" for="flexCheckVie11"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="132" id="flexCheckSab11">
                                <label class="form-check-label" for="flexCheckSab11"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="156" id="flexCheckDom11">
                                <label class="form-check-label" for="flexCheckDom11"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>12:00 - 13:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="13" id="flexCheckLun12">
                                <label class="form-check-label" for="flexCheckLun12"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="37" id="flexCheckMar12">
                                <label class="form-check-label" for="flexCheckMar12"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="61" id="flexCheckMie12">
                                <label class="form-check-label" for="flexCheckMie12"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="85" id="flexCheckJue12">
                                <label class="form-check-label" for="flexCheckJue12"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="109" id="flexCheckVie12">
                                <label class="form-check-label" for="flexCheckVie12"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="133" id="flexCheckSab12">
                                <label class="form-check-label" for="flexCheckSab12"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="157" id="flexCheckDom12">
                                <label class="form-check-label" for="flexCheckDom12"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>13:00 - 14:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="14" id="flexCheckLun13">
                                <label class="form-check-label" for="flexCheckLun13"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="38" id="flexCheckMar13">
                                <label class="form-check-label" for="flexCheckMar13"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="62" id="flexCheckMie13">
                                <label class="form-check-label" for="flexCheckMie13"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="86" id="flexCheckJue13">
                                <label class="form-check-label" for="flexCheckJue13"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="110" id="flexCheckVie13">
                                <label class="form-check-label" for="flexCheckVie13"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="134" id="flexCheckSab13">
                                <label class="form-check-label" for="flexCheckSab13"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="158" id="flexCheckDom13">
                                <label class="form-check-label" for="flexCheckDom13"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>14:00 - 15:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="15" id="flexCheckLun14">
                                <label class="form-check-label" for="flexCheckLun14"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="39" id="flexCheckMar14">
                                <label class="form-check-label" for="flexCheckMar14"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="63" id="flexCheckMie14">
                                <label class="form-check-label" for="flexCheckMie14"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="87" id="flexCheckJue14">
                                <label class="form-check-label" for="flexCheckJue14"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="111" id="flexCheckVie14">
                                <label class="form-check-label" for="flexCheckVie14"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="135" id="flexCheckSab14">
                                <label class="form-check-label" for="flexCheckSab14"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="159" id="flexCheckDom14">
                                <label class="form-check-label" for="flexCheckDom14"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>15:00 - 16:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="16" id="flexCheckLun15">
                                <label class="form-check-label" for="flexCheckLun15"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="40" id="flexCheckMar15">
                                <label class="form-check-label" for="flexCheckMar15"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="64" id="flexCheckMie15">
                                <label class="form-check-label" for="flexCheckMie15"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="88" id="flexCheckJue15">
                                <label class="form-check-label" for="flexCheckJue15"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="112" id="flexCheckVie15">
                                <label class="form-check-label" for="flexCheckVie15"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="136" id="flexCheckSab15">
                                <label class="form-check-label" for="flexCheckSab15"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="160" id="flexCheckDom15">
                                <label class="form-check-label" for="flexCheckDom15"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>16:00 - 17:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="17" id="flexCheckLun16">
                                <label class="form-check-label" for="flexCheckLun16"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="41" id="flexCheckMar16">
                                <label class="form-check-label" for="flexCheckMar16"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="65" id="flexCheckMie16">
                                <label class="form-check-label" for="flexCheckMie16"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="89" id="flexCheckJue16">
                                <label class="form-check-label" for="flexCheckJue16"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="113" id="flexCheckVie16">
                                <label class="form-check-label" for="flexCheckVie16"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="137" id="flexCheckSab16">
                                <label class="form-check-label" for="flexCheckSab16"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="161" id="flexCheckDom16">
                                <label class="form-check-label" for="flexCheckDom16"></label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>17:00 - 18:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="18" id="flexCheckLun17">
                                <label class="form-check-label" for="flexCheckLun17"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="42" id="flexCheckMar17">
                                <label class="form-check-label" for="flexCheckMar17"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="66" id="flexCheckMie17">
                                <label class="form-check-label" for="flexCheckMie17"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="90" id="flexCheckJue17">
                                <label class="form-check-label" for="flexCheckJue17"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="114" id="flexCheckVie17">
                                <label class="form-check-label" for="flexCheckVie17"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="138" id="flexCheckSab17">
                                <label class="form-check-label" for="flexCheckSab17"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="162" id="flexCheckDom17">
                                <label class="form-check-label" for="flexCheckDom17"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>18:00 - 19:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="19" id="flexCheckLun18">
                                <label class="form-check-label" for="flexCheckLun18"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="43" id="flexCheckMar18">
                                <label class="form-check-label" for="flexCheckMar18"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="67" id="flexCheckMie18">
                                <label class="form-check-label" for="flexCheckMie18"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="91" id="flexCheckJue18">
                                <label class="form-check-label" for="flexCheckJue18"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="115" id="flexCheckVie18">
                                <label class="form-check-label" for="flexCheckVie18"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="139" id="flexCheckSab18">
                                <label class="form-check-label" for="flexCheckSab18"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="163" id="flexCheckDom18">
                                <label class="form-check-label" for="flexCheckDom18"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>19:00 - 20:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="20" id="flexCheckLun19">
                                <label class="form-check-label" for="flexCheckLun19"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="44" id="flexCheckMar19">
                                <label class="form-check-label" for="flexCheckMar19"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="68" id="flexCheckMie19">
                                <label class="form-check-label" for="flexCheckMie19"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="92" id="flexCheckJue19">
                                <label class="form-check-label" for="flexCheckJue19"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="116" id="flexCheckVie19">
                                <label class="form-check-label" for="flexCheckVie19"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="140" id="flexCheckSab19">
                                <label class="form-check-label" for="flexCheckSab19"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="164" id="flexCheckDom19">
                                <label class="form-check-label" for="flexCheckDom19"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>20:00 - 21:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="21" id="flexCheckLun20">
                                <label class="form-check-label" for="flexCheckLun20"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="45" id="flexCheckMar20">
                                <label class="form-check-label" for="flexCheckMar20"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="69" id="flexCheckMie20">
                                <label class="form-check-label" for="flexCheckMie20"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="93" id="flexCheckJue20">
                                <label class="form-check-label" for="flexCheckJue20"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="117" id="flexCheckVie20">
                                <label class="form-check-label" for="flexCheckVie20"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="141" id="flexCheckSab20">
                                <label class="form-check-label" for="flexCheckSab20"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="165" id="flexCheckDom20">
                                <label class="form-check-label" for="flexCheckDom20"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>21:00 - 22:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="22" id="flexCheckLun21">
                                <label class="form-check-label" for="flexCheckLun21"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="46" id="flexCheckMar21">
                                <label class="form-check-label" for="flexCheckMar21"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="70" id="flexCheckMie21">
                                <label class="form-check-label" for="flexCheckMie21"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="94" id="flexCheckJue21">
                                <label class="form-check-label" for="flexCheckJue21"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="118" id="flexCheckVie21">
                                <label class="form-check-label" for="flexCheckVie21"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="142" id="flexCheckSab21">
                                <label class="form-check-label" for="flexCheckSab21"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="166" id="flexCheckDom21">
                                <label class="form-check-label" for="flexCheckDom21"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>22:00 - 23:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="23" id="flexCheckLun22">
                                <label class="form-check-label" for="flexCheckLun22"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="47" id="flexCheckMar22">
                                <label class="form-check-label" for="flexCheckMar22"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="71" id="flexCheckMie22">
                                <label class="form-check-label" for="flexCheckMie22"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="95" id="flexCheckJue22">
                                <label class="form-check-label" for="flexCheckJue22"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="119" id="flexCheckVie22">
                                <label class="form-check-label" for="flexCheckVie22"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="143" id="flexCheckSab22">
                                <label class="form-check-label" for="flexCheckSab22"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="167" id="flexCheckDom22">
                                <label class="form-check-label" for="flexCheckDom22"></label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>23:00 - 24:00</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="24" id="flexCheckLun23">
                                <label class="form-check-label" for="flexCheckLun23"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="48" id="flexCheckMar23">
                                <label class="form-check-label" for="flexCheckMar23"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="72" id="flexCheckMie23">
                                <label class="form-check-label" for="flexCheckMie23"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="96" id="flexCheckJue23">
                                <label class="form-check-label" for="flexCheckJue23"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="120" id="flexCheckVie23">
                                <label class="form-check-label" for="flexCheckVie23"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="144" id="flexCheckSab23">
                                <label class="form-check-label" for="flexCheckSab23"></label>
                            </div>
                        </td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="168" id="flexCheckDom23">
                                <label class="form-check-label" for="flexCheckDom23"></label>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
    </div>

</body>

</html>
