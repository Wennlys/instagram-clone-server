import { screen } from '@testing-library/react';
import React from 'react';
import { act } from 'react-dom/test-utils';
import Posts, { IPost } from '.';
import { postsResponseMock, renderWithRouter } from '../../utils/test.utils';

afterEach(() => {
  jest.resetAllMocks();
});

describe('Post component', () => {
  test('component rendering & loadPosts function calling', async () => {
    const loadedPosts: IPost[] = postsResponseMock.get.success.data;
    await act(async () => {
      renderWithRouter(<Posts posts={loadedPosts} />);
      expect(screen.getByText(/PostList/i)).toBeInTheDocument();
      screen.getAllByTestId('post-infos').map(({ textContent }) => {
        expect(textContent).toBe(String(loadedPosts[Number(textContent) - 1].id));
      });
    });
  });
});
