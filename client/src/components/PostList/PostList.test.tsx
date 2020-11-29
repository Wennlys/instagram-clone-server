import { screen } from '@testing-library/react';
import React from 'react';
import { act } from 'react-dom/test-utils';
import Posts, { Post } from '.';
import { postsResponseMock, renderWithRouter } from '../../utils/test.utils';

afterEach(() => {
  jest.resetAllMocks();
});

describe('Post component', () => {
  test('component rendering & loadPosts function calling', async () => {
    const loadedPosts: Post[] = postsResponseMock.get.success.data;
    await act(async () => {
      renderWithRouter(<Posts posts={loadedPosts} />);
      expect(screen.getByText(/PostList/i)).toBeInTheDocument();
      screen.getAllByTestId('post-infos').map((obj, idx) => {
        const img = obj.querySelector('img');
        expect(img?.src).toBe(loadedPosts[idx].image);
        const userName = obj.querySelector('span');
        expect(userName?.textContent).toBe(loadedPosts[idx].userName);
        const description = obj.querySelector('p');
        expect(description?.textContent).toBe(loadedPosts[idx].description);
      });
    });
  });
});
