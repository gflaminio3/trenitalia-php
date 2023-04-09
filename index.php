<html>

<head>
    <title>ViaggiaTreno GUI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/js/bootstrap.min.js" integrity="sha512-1/RvZTcCDEUjY/CypiMz+iqqtaoQfAITmNSJY17Myp4Ms5mdxPS5UV7iOfdZoxcGhzFbOm6sntTKJppjvuhg4g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <div class="container col-6">
        <h3 class="text-center mt-3">ViaggiaTreno API - GUI</h3>
        <form>
            <div class="form-group">
                <label for="search">Search for a station:</label>
                <div class="mb-3">
                    <input type="text" id="search" name="search" class="form-control">
                </div>
            </div>
        </form>
        <div class="list-group ul" id="results"></div>
    </div>

    <script>
        $(document).ready(function() {
            // Cache DOM elements
            var $search = $("#search");
            var $results = $("#results");

            // Load the JSON file
            $.getJSON("data/viaggiaTrenoStations.json", function(data) {
                // Save the data in a variable
                var cities = data;

                // Listen for keyup events on the search input field
                $search.on("keyup", debounce(function() {
                    // Get the search term from the input field
                    var searchTerm = $search.val().toUpperCase();

                    // Clear any previous search results
                    $results.empty();

                    // Display the search results
                    var found = false;
                    for (var city in cities) {
                        if (city.toUpperCase().indexOf(searchTerm) !== -1) {
                            found = true;
                            $results.append(
                                '<li><a href="board.php?code=' + cities[city] + '">' + city + "</a></li>"
                            );
                        }
                    }

                    // Display a message if no results were found
                    if (!found) {
                        $results.append("<li>No results found.</li>");
                    }
                }, 250));
            });

            // Debounce function
            function debounce(func, wait, immediate) {
                var timeout;
                return function() {
                    var context = this,
                        args = arguments;
                    var later = function() {
                        timeout = null;
                        if (!immediate) func.apply(context, args);
                    };
                    var callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                    if (callNow) func.apply(context, args);
                };
            }
        });
    </script>
</body>

</html>