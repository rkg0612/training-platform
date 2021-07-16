const generateYesOrNoRow = (column) => {
  let initialRow = `<td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d; 
    font-family: sans-serif; font-size: 13px; text-align: center;">Yes</td>`;

  for (let currentColumn = 0; currentColumn <= column; currentColumn++) {
    initialRow += `<td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d; 
    font-family: sans-serif; font-size: 13px; text-align: center;">Yes</td>`;
  }

  return initialRow;
};

const generateHeader = (column) => {
  let initialRow = `<td width="90" valign="middle" height="50" class="invReg"
    style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: center; font-weight: 800;">
    Dealer A</td>`;

  for (let currentColumn = 0; currentColumn <= column; currentColumn++) {
    initialRow += `<td width="90" valign="middle" height="50" class="invReg"
                style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: center; font-weight: 800;">
                Dealer ${currentColumn}</td>`;
  }

  return initialRow;
};

const generateScore = (column) => {
  let initialRow = `<td width="90" valign="middle" height="50" class="invReg"
    style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: center; font-weight: 800;">
    5</td>`;

  for (let currentColumn = 0; currentColumn <= column; currentColumn++) {
    initialRow += `<td width="90" valign="middle" height="50" class="invReg"
                style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: center; font-weight: 800;">
                5</td>`;
  }

  return initialRow;
};

const secretShopper = (column) => {
  return `<figure class="table">
<table align="center" cellpadding="0" cellspacing="0" border="0"
    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
    <tbody>
        <tr style="background-color: #f9f9f9;">
            <td width="200" valign="middle" height="50" class="invReg"
                style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: left; font-weight: 800;">
            </td>
            ${generateHeader(column)}
        </tr>
        <tr style="background-color: #f4f4f4;">
            <td width="200" valign="middle" height="50" class="invReg"
                style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: left; font-weight: 800;">
                Obtained Customer's Phone Number</td>
                ${generateYesOrNoRow(column)}
    </tr>
        <tr style="background-color: #f9f9f9;">
            <td width="200" valign="middle" height="50" class="invReg"
                style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: left; font-weight: 800;">
                Obtained Customer's Email Address</td>
                ${generateYesOrNoRow(column)}
        </tr>
        <tr style="background-color: #f4f4f4;">
            <td width="200" valign="middle" height="50" class="invReg"
                style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: left; font-weight: 800;">
                Confirmed Customer's Wants and Needs</td>
               
                ${generateYesOrNoRow(column)}
        </tr>
        <tr style="background-color: #f9f9f9;">
            <td width="200" valign="middle" height="50" class="invReg"
                style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: left; font-weight: 800;">
                Asked for an Appointment</td>
                ${generateYesOrNoRow(column)}
        </tr>
        <tr style="background-color: #f9f9f9;">
            <td width="200" valign="middle" height="50" class="invReg"
                style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: left; font-weight: 800;">
                Provided their Contact Information</td>
                ${generateYesOrNoRow(column)}
        </tr>
        <tr style="background-color: #f4f4f4;">
            <td width="200" valign="middle" height="50" class="invReg"
                style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: left; font-weight: 800;">
                Followed the Inbound Call Guide (Bonus Point)</td>
                ${generateYesOrNoRow(column)}
        </tr>
        <tr style="background-color: #f9f9f9;">
            <td width="200" valign="middle" height="50" class="invReg"
                style="color: #7f8c9d; font-family: sans-serif; font-size: 13px; text-align: left; font-weight: 800;">
                Total Score</td>
                ${generateScore(column)}
        </tr>
    </tbody>
</table>
</figure>`;
};

// const generateShopper

export { secretShopper };
