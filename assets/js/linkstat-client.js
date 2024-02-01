const linkstatClient = {
    emec: 0,
    listener: false,

    click: async (elementId, linkid, key) => {
        const link = document.getElementById(elementId);

        const span = document.createElement('span');
        span.setAttribute('id', 'wait');
        span.innerHTML = ' Переход...';
        link.appendChild(span);

        const data = new FormData;

        data.append('linkstat-button-linkid', linkid);
        data.append('linkstat-button-key', key);

        const request = await fetch(
            '/wp-json/linkstat/v1/click',
            {
                method: 'POST',
                credentials: 'include',
                body: data
            }
        );

        if (request.ok)
        {
            const answer = await request.json();

            if (answer.code == 0) 
                console.log('linkstatClient.click(): success.');
            else 
                console.error('linkstatClient.click(): API error: ' + answer.code);
        }
        else 
            console.error('linkstatClient.click(): network error.');
    }
};
