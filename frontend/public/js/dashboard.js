document.addEventListener('DOMContentLoaded', function() {
    // Load skills
    fetch('api/skills.php')
        .then(response => response.json())
        .then(skills => {
            const container = document.getElementById('skills-container');
            container.innerHTML = '';
            
            skills.forEach(user => {
                container.innerHTML += `
                    <div class="profile-card mb-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>${user.name}</h5>
                                <p><strong>Location:</strong> ${user.location || 'Not specified'}</p>
                                <p><strong>Skills Offered:</strong><br>
                                    ${user.skills_offered?.map(skill => 
                                        `<span class="skill-tag">${skill}</span>`
                                    ).join('')}
                                </p>
                                <p><strong>Skills Wanted:</strong><br>
                                    ${user.skills_wanted?.map(skill => 
                                        `<span class="skill-tag">${skill}</span>`
                                    ).join('')}
                                </p>
                                <p><strong>Availability:</strong> 
                                    <span class="badge availability-badge">${user.availability}</span>
                                </p>
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <a href="request.php?user_id=${user.id}" class="btn btn-outline-info">Request Swap</a>
                            </div>
                        </div>
                    </div>
                `;
            });
        });

    // Load requests
    fetch('api/requests.php')
        .then(response => response.json())
        .then(requests => {
            const container = document.getElementById('requests-container');
            container.innerHTML = '';
            
            if (requests.length === 0) {
                container.innerHTML = '<p class="text-muted">No recent requests</p>';
                return;
            }
            
            requests.forEach(request => {
                container.innerHTML += `
                    <div class="alert alert-${request.status === 'pending' ? 'info' : 'success'} mb-2">
                        <strong>${request.from_user}</strong> wants your <strong>${request.skill_name}</strong>
                        <div class="d-flex justify-content-between mt-2">
                            <span class="badge bg-${request.status === 'pending' ? 'warning' : 'success'}">
                                ${request.status}
                            </span>
                            <small>${request.date}</small>
                        </div>
                    </div>
                `;
            });
        });

    // Search functionality
    document.getElementById('searchForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const query = document.getElementById('skillSearch').value;
        
        fetch(`api/skills.php?search=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(skills => {
                // Update skills container
            });
    });
});