function getLogedInUser(){
    axios.get(`/logged_in_user`).
    then((res) => {
        return res.data;
    });
};

function exportData(format, slug, url)
{
    console.log('log');
}

export default getLogedInUser;