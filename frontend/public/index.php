<?php
require_once __DIR__ . '/../../frontend/includes/header.php';

// Fetch and decode API response
$api_response = file_get_contents('http://localhost/skill_swap_platform/frontend/public/api/skills.php?user_id=123');
$data = json_decode($api_response, true); // Ensure this returns an array

// Debugging: Check the response structure
echo '<pre>API Response: ';
print_r($data);
echo '</pre>';

// Only proceed if we got valid data
if (is_array($data) && !isset($data['error'])) {
?>
    <div class="container">
        <h1>Skill Swap Platform</h1>
        
        <div class="skills-list">
            <h2>Your Skills</h2>
            <?php foreach ($data as $skill): ?>
                <?php if (is_array($skill)): ?>
                    <div class="skill-card">
                        <h3><?= htmlspecialchars($skill['name'] ?? 'No name') ?></h3>
                        <p>Type: <?= $skill['type'] ?? 'No type' ?></p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php
} else {
    echo '<div class="error">Failed to load skills: ' . ($data['error'] ?? 'Unknown error') . '</div>';
}

require_once __DIR__ . '/../../frontend/includes/footer.php';
?>