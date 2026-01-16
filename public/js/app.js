$(document).ready(function() {
    // Cache DOM elements
    const $sqlForm = $('#sqlForm');
    const $sqlQuery = $('#sqlQuery');
    const $results = $('#results');
    const $analyzeBtn = $('#analyzeBtn');
    
    // Handle form submission
    $sqlForm.on('submit', function(e) {
        e.preventDefault();
        analyzeSQL();
    });
    
    // Analyze SQL function
    function analyzeSQL() {
        const sql = $sqlQuery.val().trim();
        
        if (!sql) {
            showError('Please enter a SQL query to analyze');
            return;
        }
        
        // Get selected rules
        const selectedRules = [];
        $('input[name="rules[]"]:checked').each(function() {
            selectedRules.push($(this).val());
        });
        
        // Show loading state
        setLoading(true);
        
        // Clear previous results
        $results.empty();
        
        // Make AJAX request
        $.ajax({
            url: '/analyze',
            method: 'POST',
            data: { 
                sql: sql,
                rules: selectedRules
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayResults(response);
                } else {
                    showError(response.error || 'An unknown error occurred');
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Error analyzing SQL query';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMsg = xhr.responseJSON.error;
                }
                showError(errorMsg);
            },
            complete: function() {
                setLoading(false);
            }
        });
    }
    
    // Display analysis results
    // In the displayResults function, update it to handle the new format
    function displayResults(data) {
        $results.empty();

        // Show errors
        if (data.errors && data.errors.length > 0) {
            // console.log(data.errors);return

            data.errors.forEach(function(error) {
                if (typeof error === 'object' && error !== null) {
                    addIssue('error', 'Error', error.message || error);
                } else {
                    addIssue('error', 'Error', error);
                }
            });
        }

        // Show warnings
        if (data.warning && data.warning.length > 0) {
            data.warning.forEach(function(warning) {
                if (typeof warning === 'object' && warning !== null) {
                    addIssue('warning', 'Warning', warning.message || warning);
                } else {
                    addIssue('warning', 'Warning', warning);
                }
            });
        }

        // Show general issues
        if (data.issues && data.issues.length > 0) {
            data.issues.forEach(function(issue) {
                if (typeof issue === 'object' && issue !== null) {
                    addIssue('warning', 'Issue', issue.message || issue);
                } else {
                    addIssue('warning', 'Issue', issue);
                }
            });
        }

        // Show success message if no issues found
        if (!data.errors?.length && !data.warning?.length && !data.issues?.length) {
            addIssue('success', 'Success', 'No issues found in your SQL query!');
        }
    }
    
    // Add an issue to the results
    function addIssue(type, title, message) {
        const icon = getIconForType(type);
        const $issue = $(`
            <div class="issue ${type}">
                <i class="${icon}"></i>
                <div class="issue-content">
                    <div class="issue-title">${title}</div>
                    <div class="issue-desc">${message}</div>
                </div>
            </div>
        `);
        $results.append($issue);
    }
    
    // Get icon for issue type
    function getIconForType(type) {
        const icons = {
            'error': 'fas fa-exclamation-circle',
            'warning': 'fas fa-exclamation-triangle',
            'success': 'fas fa-check-circle'
        };
        return icons[type] || 'fas fa-info-circle';
    }
    
    // Show error message
    function showError(message) {
        $results.html(`
            <div class="issue error">
                <i class="fas fa-exclamation-circle"></i>
                <div class="issue-content">
                    <div class="issue-title">Error</div>
                    <div class="issue-desc">${message}</div>
                </div>
            </div>
        `);
    }
    
    // Set loading state
    function setLoading(isLoading) {
        if (isLoading) {
            $analyzeBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Analyzing...');
        } else {
            $analyzeBtn.prop('disabled', false).html('Analyze SQL');
        }
    }
    
    // Add some keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl+Enter to submit form
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            analyzeSQL();
        }
    });
});
