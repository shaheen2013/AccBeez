function getLogedInUser(){
    axios.get(`/logged_in_user`).
    then((res) => {
        return res.data;
    });
};

export default getLogedInUser;