function showLeaderboard() {
    const leaderboardContainer = document.getElementById('leaderboard-container');
    leaderboardContainer.style.display = 'block';

    // Use AJAX to retrieve leaderboard data from the server (PHP)
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const leaderboardData = JSON.parse(xhr.responseText);
            displayLeaderboard(leaderboardData);
        }
    };
    xhr.open('GET', 'leaderboard.php', true);
    xhr.send();
}

function displayLeaderboard(leaderboardData) {
    const leaderboardTable = document.createElement('table');
    leaderboardTable.innerHTML = `
        <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Score</th>
        </tr>
    `;

    for (let i = 0; i < leaderboardData.length; i++) {
        const row = `
            <tr>
                <td>${i + 1}</td>
                <td>${leaderboardData[i].name}</td>
                <td>${leaderboardData[i].score}</td>
            </tr>
        `;
        leaderboardTable.innerHTML += row;
    }

    document.getElementById('leaderboard-container').appendChild(leaderboardTable);
}
