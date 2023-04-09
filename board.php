<html>

<head>
    <title>ViaggiaTreno GUI</title>
    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container col-6">
        <h3 class="text-center mt-3 pb-3">ViaggiaTreno API - GUI</h3>
        <div class="card">
            <div class="card-body">
                <h2>Arrivals</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="mb-3">
                        <table class="table table-striped" id="arrivalsTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>From</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="card">
            <div class="card-body">
                <h2>Departures</h2>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="mb-3">
                        <table class="table table-striped" id="departuresTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>To</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load the JSON file
        var data = JSON.parse(<?php require 'api.php'; echo json_encode(getStationBoard($_GET['code'])); ?>);

        // Initialize the DataTable
        $(document).ready(function() {
            $('#arrivalsTable').DataTable({
                data: data.arrivals,
                columns: [{
                        title: "Name",
                        data: "name"
                    },
                    {
                        title: "From",
                        data: "location"
                    },
                    {
                        title: "Time",
                        data: "time"
                    },
                    {
                        title: "Type",
                        data: "type"
                    }
                ]
            });

            $('#departuresTable').DataTable({
                data: data.departures,
                columns: [{
                        title: "Name",
                        data: "name"
                    },
                    {
                        title: "To",
                        data: "location"
                    },
                    {
                        title: "Time",
                        data: "time"
                    },
                    {
                        title: "Type",
                        data: "type"
                    }
                ]
            });
        });
    </script>
</body>
</html>