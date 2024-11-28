<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    $agentTable = "Agent";
    $agencyTable = "Agency";
    $ratingsTable = "Rating";

    // SQL query to retrieve agent details along with agency and review info
    $query = "
        SELECT 
            agent.id,
            agent.first_name, 
            agent.surname, 
            agent.email, 
            agent.phone, 
            IFNULL(agency.agency_name, 'NO AGENCY RECORDED') AS agency_name, 
            IFNULL(agency.agency_address, 'NO AGENCY RECORDED') AS agency_address,
            COUNT(rating.id) AS review_count,
            AVG(rating.rating) AS average_rating
        FROM $agentTable
        LEFT JOIN $agencyTable ON agent.agency_id = agency.id
        LEFT JOIN $ratingsTable ON agent.id = rating.agent_id
        GROUP BY agent.id, agent.first_name, agent.surname, agent.email, agent.phone, agency.agency_name, agency.agency_address
    ";

    // Handle the order parameter for sorting
    $order = $request->input('order', 'default');

    // Apply the order logic based on user selection
    switch ($order) {
        case 'default_desc':
            $sql = $query . " ORDER BY agent.surname DESC, agent.first_name DESC";
            break;
        case 'review_count':
            $sql = $query . " ORDER BY review_count ASC";
            break;
        case 'review_count_desc':
            $sql = $query . " ORDER BY review_count DESC";
            break;
        case 'review_average':
            $sql = $query . " ORDER BY average_rating ASC";
            break;
        case 'review_average_desc':
            $sql = $query . " ORDER BY average_rating DESC";
            break;
        default:
            $sql = $query . " ORDER BY agent.surname ASC, agent.first_name ASC";
            break;
    }

    // Execute the SQL query and return the view with data
    $data = DB::select($sql);
    return view('index')->with('data', $data)->withFragment('agent-listings');
})->name('home');

Route::get('/add-agency', function () {
    $agencyTable = "Agency";
    $agentTable = "Agent";
    $ratingsTable = "Rating";

    // SQL query to fetch agencies and their average ratings
    $sql = "
        SELECT 
            agency.id AS agency_id,
            agency.agency_name,
            agency.agency_address,
            AVG(rating.rating) AS average_rating
        FROM $agencyTable agency
        LEFT JOIN $agentTable agent ON agency.id = agent.agency_id
        LEFT JOIN $ratingsTable rating ON agent.id = rating.agent_id
        WHERE agency.id IS NOT NULL
        GROUP BY agency.id, agency.agency_name, agency.agency_address
        ORDER BY agency.agency_name ASC
    ";

    // Execute the query and return the view with data
    $data = DB::select($sql);
    return view('add-agency')->with('data', $data);
})->name('add-agency');

Route::post('/add-agency', function (Request $request) {
    $tableName = "Agency";

    // Sanitise agency input fields
    $agencyName = htmlspecialchars(trim($request->input('agency_name')), ENT_QUOTES, 'UTF-8');
    $agencyAddress = htmlspecialchars(trim($request->input('agency_address')), ENT_QUOTES, 'UTF-8');
    $errorMessage = "";
    $successMessage = "";

    // Disallowed characters for validation
    $disallowed_chars = '/[-_+"]/';
    
    // Validate agency name and address
    if (strlen($agencyName) < 2 || preg_match($disallowed_chars, $agencyName)) {
        $errorMessage .= "<p><strong>ERROR:</strong> Agency Name must be at least 2 characters long and cannot contain -, _, +, or \" characters.</p>";
    }

    if (strlen($agencyAddress) < 2 || preg_match($disallowed_chars, $agencyAddress)) {
        $errorMessage .= "<p><strong>ERROR:</strong> Agency Address must be at least 2 characters long and cannot contain -, _, +, or \" characters.</p>";
    }

    // Redirect back if validation fails
    if (!empty($errorMessage)) {
        return redirect()->back()->with('error', $errorMessage)->withInput();
    }

    // Check if agency already exists
    $searchAgency = DB::select("SELECT * FROM $tableName WHERE agency_name = ?", [$agencyName]);

    // Update or insert agency record based on existence
    if (!empty($searchAgency)) {
        $updateQuery = DB::update("UPDATE $tableName SET agency_address = ? WHERE agency_name = ?", [$agencyAddress, $agencyName]);
        if (!$updateQuery) {
            $errorMessage = "<p><strong>ERROR:</strong> Update failed. Please try again.</p>";
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
        $successMessage = "<strong>SUCCESS:</strong> The Agency Record Has Been Updated...";
    } else {
        $insertQuery = DB::insert("INSERT INTO $tableName (agency_name, agency_address) VALUES (?, ?);", [$agencyName, $agencyAddress]);
        if (!$insertQuery) {
            $errorMessage = "<p><strong>ERROR:</strong> Update failed. Please try again.</p>";
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
        $successMessage = "<strong>SUCCESS:</strong> A New Agency Record Has Been Created...";
    }

    // Redirect back with success message
    return redirect()->back()->with('success', $successMessage);
});

Route::delete('/delete-agency/{id}', function ($id) {
    $tableName = 'Agency';

    // Delete the agency record
    $deleteAgency = DB::delete("DELETE FROM $tableName WHERE id = ?", [$id]);
    if ($deleteAgency) {
        return redirect()->back()->with('success', '<strong>SUCCESS:</strong> Agency Record Has Been Deleted...');
    } else {
        return redirect()->back()->with('error', '<p><strong>ERROR:</strong> Deletion failed. Please try again.</p>');
    }
})->name('delete-agency');

Route::get('/add-agent', function () {
    $tableName = 'Agency';
    
    // Fetch all agencies for the dropdown
    $agencyData = DB::select("SELECT * FROM $tableName ORDER BY agency_name ASC");
    return view('add-agent')->with('agencyData', $agencyData);
})->name('add-agent');

Route::post('/add-agent', function (Request $request) {
    $agentTable = "Agent";
    $agencyTable = "Agency";

    // Sanitise agent input fields
    $firstName = htmlspecialchars(trim($request->input('first_name')), ENT_QUOTES, 'UTF-8');
    $surname = htmlspecialchars(trim($request->input('surname')), ENT_QUOTES, 'UTF-8');
    $position = htmlspecialchars(trim($request->input('position')), ENT_QUOTES, 'UTF-8');
    $biography = htmlspecialchars(trim($request->input('biography')), ENT_QUOTES, 'UTF-8');
    $phone = htmlspecialchars(trim($request->input('phone')), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($request->input('email')), ENT_QUOTES, 'UTF-8');
    $agencyId = htmlspecialchars(trim($request->input('agency_id')), ENT_QUOTES, 'UTF-8');
    $errorMessage = "";
    $successMessage = "";

    // Validate agent details and check for errors
    $disallowed_chars = '/[-_+"]/';

    if (strlen($firstName) < 2 || preg_match($disallowed_chars, $firstName)) {
        $errorMessage .= "<p><strong>ERROR:</strong> First Name must be at least 2 characters long and cannot contain -, _, +, or \" characters.</p>";
    }

    if (strlen($surname) < 2 || preg_match($disallowed_chars, $surname)) {
        $errorMessage .= "<p><strong>ERROR:</strong> Surname must be at least 2 characters long and cannot contain -, _, +, or \" characters.</p>";
    }

    if (strlen($position) < 2 || preg_match($disallowed_chars, $position)) {
        $errorMessage .= "<p><strong>ERROR:</strong> Position must be at least 2 characters long and cannot contain -, _, +, or \" characters.</p>";
    }

    if (strlen($biography) < 100) {
        $errorMessage .= "<p><strong>ERROR:</strong> Biography must be at least 100 characters long.</p>";
    }

    if (strlen($phone) === 0) {
        $errorMessage .= "<p><strong>ERROR:</strong> Phone number is a required field.</p>";
    }

    if (strlen($email) === 0) {
        $errorMessage .= "<p><strong>ERROR:</strong> Email address is a required field.</p>";
    }

    // Validate selected agency
    $validateAgency = DB::select("SELECT * FROM $agencyTable WHERE id = ?", [$agencyId]);
    if (!$validateAgency) {
        $errorMessage .= "<p><strong>ERROR:</strong> Unable to find Agency selection. Please try again.</p>";
    }

    // Redirect back if validation fails
    if (!empty($errorMessage)) {
        return redirect()->back()->with('error', $errorMessage)->withInput();
    }

    // Check if agent already exists by email
    $searchAgent = DB::select("SELECT * FROM $agentTable WHERE email = ?", [$email]);

    if (!empty($searchAgent)) {
        // Update existing agent details
        $updateQuery = DB::update("UPDATE $agentTable SET first_name = ?, surname = ?, position = ?, biography = ?, phone = ?, agency_id = ? WHERE email = ?", [
            $firstName,
            $surname,
            $position,
            $biography,
            $phone,
            $agencyId,
            $email
        ]);

        if (!$updateQuery) {
            $errorMessage = "<p><strong>ERROR:</strong> Update failed. Please try again.</p>";
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }

        $successMessage = "<strong>SUCCESS:</strong> The Agent Record Has Been Updated...";
    } else {
        // Insert new agent record
        $insertQuery = DB::insert("INSERT INTO $agentTable (first_name, surname, position, biography, phone, email, agency_id) VALUES (?, ?, ?, ?, ?, ?, ?)", [
            $firstName,
            $surname,
            $position,
            $biography,
            $phone,
            $email,
            $agencyId
        ]);

        if (!$insertQuery) {
            $errorMessage = "<p><strong>ERROR:</strong> Insertion failed. Please try again.</p>";
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }

        $successMessage = "<strong>SUCCESS:</strong> A New Agent Record Has Been Created...";
    }

    // Redirect back with success message
    return redirect()->back()->with('success', $successMessage);
});

Route::get('/view-agent/{agentNumber}', function ($agentNumber) {
    $agentTable = "Agent";
    $agencyTable = "Agency";
    $reviewTable = "Rating";

    // Fetch agent details by ID
    $agent = DB::select("SELECT * FROM $agentTable WHERE id = ?", [$agentNumber]);
    $reviews = DB::select("SELECT * FROM $reviewTable WHERE agent_id = ?", [$agentNumber]);

    if (!empty($agent)) {
        // Fetch agency directly via the agent's agency_id
        $agency = DB::select("SELECT * FROM $agencyTable WHERE id = ?", [$agent[0]->agency_id]);

        return view('view-agent')->with([
            'agent' => $agent[0],
            'agency' => !empty($agency) ? $agency[0] : null,
            'reviews' => $reviews
        ]);
    } else {
        abort(404, 'Agent not found');
    }
})->name('view-agent');

Route::post('/view-agent/{agentNumber}', function (Request $request, $agentNumber) {
    $reviewTable = "Rating";

    // Sanitise review input fields
    $reviewerName = htmlspecialchars(trim($request->input('reviewer_name')), ENT_QUOTES, 'UTF-8');
    $alteredName = process_username($reviewerName);
    $reviewHeading = htmlspecialchars(trim($request->input('review_heading')), ENT_QUOTES, 'UTF-8');
    $reviewContent = htmlspecialchars(trim($request->input('review_content')), ENT_QUOTES, 'UTF-8');
    $starRating = htmlspecialchars(trim($request->input('star_rating')), ENT_QUOTES, 'UTF-8');
    $currentDate = date('d-m-Y');
    $ipAddress = exec('curl https://api.ipify.org');
    $errorMessage = "";
    $successMessage = "";

    if ($reviewerName !== $alteredName) {
        $successMessage .= "<p><strong>PLEASE NOTE:</strong> Reviewer's Name has been altered.</p>";
    }

    // Validate review details
    $disallowed_chars = '/[-_+"]/';

    if (strlen($reviewerName) < 2 || preg_match($disallowed_chars, $reviewerName)) {
        $errorMessage .= "<p><strong>ERROR:</strong> Reviewer's Name must be at least 2 characters long and cannot contain -, _, +, or \" characters.</p>";
    }

    if (strlen($reviewHeading) < 10) {
        $errorMessage .= "<p><strong>ERROR:</strong> Review Heading must be at least 10 characters long.</p>";
    }

    if (strlen($reviewContent) < 30) {
        $errorMessage .= "<p><strong>ERROR:</strong> Review Content must be at least 30 characters long.</p>";
    }

    if (!is_numeric($starRating) || $starRating < 1 || $starRating > 5) {
        $errorMessage .= "<p><strong>ERROR:</strong> Rating must be a number between 1 and 5.</p>";
    }

    // Redirect back if validation fails
    if (!empty($errorMessage)) {
        return redirect()->back()->with('error-review', $errorMessage)->withInput()->withFragment('review-form');
    }

    // Check for duplicate reviews
    $flaggedReview = 0;
    if (check_review($alteredName, $ipAddress)) {
        $flaggedReview = 1;
    }

    // Update or insert the review
    $searchUser = DB::select("SELECT id FROM $reviewTable WHERE reviewer_name = ? AND agent_id = ?", [$alteredName, $agentNumber]);

    if ($searchUser) {
        $updateQuery = DB::update("UPDATE $reviewTable SET review_heading = ?, review = ?, rating = ?, ip_address = ?, date = ?, flagged_review = ? WHERE id = ?", [
            $reviewHeading,
            $reviewContent,
            $starRating,
            $ipAddress,
            $currentDate,
            $flaggedReview,
            $searchUser[0]->id,
        ]);

        if (!$updateQuery) {
            $errorMessage = "<p><strong>ERROR:</strong> Review update failed. Please try again.</p>";
            return redirect()->back()->with('error-review', $errorMessage)->withInput();
        }

        $successMessage .= "<strong>SUCCESS:</strong> The Review Has Been Updated Successfully.";
    } else {
        $insertQuery = DB::insert("INSERT INTO $reviewTable (agent_id, reviewer_name, review_heading, review, rating, ip_address, date, flagged_review) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [
            $agentNumber,
            $alteredName,
            $reviewHeading,
            $reviewContent,
            $starRating,
            $ipAddress,
            $currentDate,
            $flaggedReview
        ]);

        if (!$insertQuery) {
            $errorMessage = "<p><strong>ERROR:</strong> Review submission failed. Please try again.</p>";
            return redirect()->back()->with('error-review', $errorMessage)->withInput();
        }

        $successMessage .= "<strong>SUCCESS:</strong> The Review Has Been Submitted Successfully.";
    }

    // Add the username to session storage
    session()->put('userName', $alteredName);
    return redirect()->back()->with('success-review', $successMessage)->withFragment('review-form');
})->name('view-agent');

Route::delete('/delete-agent/{agentNumber}', function ($agentNumber) {
    $tableName = 'Agent';

    // Delete the agent record
    $deleteAgent = DB::delete("DELETE FROM $tableName WHERE id = ?", [$agentNumber]);
    if (!$deleteAgent) {
        return redirect()->back()->with('error-delete', '<p><strong>ERROR:</strong> Deletion failed. Please try again.</p>');
    }

    return redirect()->route('home');
})->name('delete-agent');

Route::get('/view-agency/{agencyNumber}', function ($agencyNumber) {
    $agentTable = "Agent";
    $agencyTable = "Agency";
    $ratingTable = "Rating";

    // Fetch agency details by ID
    $agency = DB::select("SELECT * FROM $agencyTable WHERE id = ?", [$agencyNumber]);

    if (!empty($agency)) {
        // Fetch agent details for the agency
        $agentList = DB::select("
            SELECT 
            agent.id,
            agent.first_name, 
            agent.surname, 
            agent.email, 
            agent.phone, 
            agent.position,
            COUNT(rating.id) AS review_count,
            AVG(rating.rating) AS average_rating
        FROM $agentTable agent
        LEFT JOIN $ratingTable rating ON agent.id = rating.agent_id
        WHERE agent.agency_id = ?
        GROUP BY agent.id, agent.first_name, agent.surname, agent.email, agent.phone, agent.position
        ", [$agencyNumber]);

        return view('view-agency', [
            'agency' => $agency[0],
            'agents' => $agentList
        ]);
    } else {
        abort(404, 'Agency not found');
    }
})->name('view-agency');
