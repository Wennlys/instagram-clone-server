import { AxiosResponse } from 'axios';
import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import Posts, { Post } from '../../components/PostList';
import api from '../../services/api';

function Home(): JSX.Element {
  const [posts, setPosts] = useState<Post[] | []>([]);
  useEffect(() => {
    async function fetchPosts() {
      const response: AxiosResponse = await api.get('/posts', {
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const returnedPosts: Post[] | [] = response.data.data ?? [];
      if (response.status === 200) setPosts(returnedPosts);
    }

    fetchPosts();
  }, []);

  return (
    <>
      <Link to="/explore">Explore</Link>
      <div>Home</div>
      <Posts posts={posts} />
    </>
  );
}

export default Home;
