import { AxiosResponse } from 'axios';
import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Posts, { Post } from '../../components/PostList';
import api from '../../services/api';

function Home(): JSX.Element {
  const [posts, setPosts] = useState<Post[] | []>([]);
  useEffect(() => {
    async function fetchPosts() {
      const response: AxiosResponse<Post[]> = await api.get('/posts');
      setPosts(response.status === 200 ? response.data : []);
    }

    fetchPosts();
  });

  return (
    <>
      <Link to="/explore">Explore</Link>
      <div>Home</div>
      <Posts posts={posts} />
    </>
  );
}

export default Home;
