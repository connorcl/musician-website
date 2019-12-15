<?php

// sanitize input by trimming, unquoting and escaping
function sanitize($str) {
  return trim(stripslashes(htmlspecialchars($str)));
}

// format a column name so it is usable
// as a PDO prepared statement parameter
function formatParamName($colName) {
  // replace period with triple underscore
  return str_replace('.', '___', $colName);
}

// execute a prepared statement
function executePreparedStmt($query, $params, $all = true, $fetch = true) {
  // access global database connection
  global $conn;
  try {
    // prepare statement
    $stmt = $conn->prepare($query);
    // bind parameters
    foreach (array_keys($params) as $param) {
      if ($param == ":lim" || $param == ":offs") {
        $stmt->bindParam($param, $params[$param], PDO::PARAM_INT);
      } else {
        $stmt->bindParam($param, $params[$param]);
      }
    }
    // execute prepared statement
    $stmt->execute();
    // get result
    $result = true;
    if ($fetch) {
      if ($all) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      } else {
         $result = $stmt->fetch(PDO::FETCH_ASSOC);
      }
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  } finally {
    unset($stmt);
  }
  // return result
  return $result;
}

// EOF
