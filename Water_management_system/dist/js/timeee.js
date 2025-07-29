    function setTime() {
        var d = new Date(),
            el = document.getElementById("time");

        el.innerHTML = formatAMPM(d);

        setTimeout(setTime, 1000);
    }

    function formatAMPM(date) {
        // var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var months = ["មករា", "កុម្ភៈ", "មីនា", "មេសា", "ឧសភា", "មិថុនា", "កក្កដា", "សីហា", "កញ្ញា", "តុលា", "វិច្ឆិកា", "ធ្នូ"];
        var
            year = date.getFullYear(),
            day = date.getDate(),
            month = months[date.getMonth()],
            hours = date.getHours(),
            minutes = date.getMinutes(),
            seconds = date.getSeconds(),
            ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = day + '&nbsp/ ' + month + ' / ' + year + '&nbsp' + hours + ':' + minutes + ':' + seconds + ' ' + ampm;
        return strTime;
    }

    setTime();